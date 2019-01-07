const request = require('request');
const fs = require('fs');
const express = require('express');
const util = require('util');
const app = express();
const rcon = require('rcon');
const bodyParser = require("body-parser");
const dotenv = require('dotenv').config({path: __dirname + '/.env'});
const logReceiver = require("srcds-log-receiver");
const cors = require('cors');
const io = require('socket.io')();
const redis = require('redis');
const redisC = redis.createClient(); //creates a new client

/***********************
 *    CONFIGURATION    *
 ***********************/
app.use(bodyParser.urlencoded({extended: false}));
app.use(bodyParser.json());
app.use(cors());

/*******************
 *    CONSTANTS    *
 *******************/
const HTTP_PORT = 10000;
const REDIS_KEY = 'messages';
const REDIS_STAGING_KEY = 'messages_staging';
// TODO: https://api.ipify.org?format=json
const LISTENING_IP = '104.156.246.245';
const DATE_NOW = Date.now();
const LOGS_PATH = __dirname + '/logs/logs' + DATE_NOW + '.log';
const STDOUT_PATH = __dirname + '/logs/stdout' + DATE_NOW + '.log';
const STDERR_PATH = __dirname + '/logs/errout' + DATE_NOW + '.log';

/*********************
 *    WEB LOGGING    *
 *********************/
let log_file = fs.createWriteStream(LOGS_PATH, {flags: 'w'});
let log_stdout = process.stdout;

let out_file = fs.createWriteStream(STDOUT_PATH);
let err_file = fs.createWriteStream(STDERR_PATH);

process.stdout.oldWrite = process.stdout.write;
process.stderr.oldWrite = process.stdout.write;

process.stdout.write = (a) => {
    process.stdout.oldWrite(a);
    out_file.write.bind(out_file);
};
process.stderr.write = (a) => {
    process.stderr.oldWrite(a);
    err_file.write.bind(err_file);
};

console.log = function (d) { //
    log_file.write(util.format(d) + '\n');
    log_stdout.write(util.format(d) + '\n');
};

process.on('uncaughtException', function (err) {
    console.error((err && err.stack) ? err.stack : err);
});

/************************
 *    EVENT HANDLING    *
 ************************/
redisC.on('connect', function () {
    console.log('REDIS is connected');
    redisConnected = true;
});

/*******************
 *    VARIABLES    *
 *******************/
// Server data
let servers = [];
let dataCount = 0;
let redisConnected = false;

/*******************
 *    FUNCTIONS    *
 *******************/
function Server(hostname, name, ip, port, rconPassword, receiverPort) {
    this.hostname = hostname;
    this.name = name;
    this.ip = ip;
    this.port = port;
    this.rconPassword = rconPassword;
    this.receiverPort = receiverPort;

    this.authed = false;

    this.receiver = undefined;
    this.connection = undefined;

    this.onConnectionAuth = [];
    this.onConnectionResponse = [];
    this.onConnectionEnd = [];
    this.onConnectionError = [];

    this.onReceiverData = [];
    this.onReceiverInvalid = [];

}

Server.prototype = {
    constructor: Server,

    boot: function () {
        this.startRconConnection();
        this.startReceiver();
    },

    startRconConnection: function () {
        this.connection = new rcon(this.ip, this.port, this.rconPassword);

        let that = this;

        this.connection.on('auth', function () {
            that.log(`Authenticated RCON!`);

            that.bindReceiver();
            that.authed = true;

            for (let i = that.onConnectionAuth.length - 1; i >= 0; i--) {
                let cb = that.onConnectionAuth[i];
                if (cb() === true)
                    that.onConnectionAuth.splice(i, 1);
            }

        }).on('response', function (str) {
            // that.log(`Responded from RCON: ${str}`);

            redisC.rpush([REDIS_STAGING_KEY, that.ip + ':' + that.port + ' - ' + str], function (err, reply) {
                if (err) {
                    that.log(err);
                } else {
                    that.log(reply);
                }
            });
            redisC.rpush([REDIS_KEY, that.ip + ':' + that.port + ' - ' + str], function (err, reply) {
                if (err) {
                    that.log(err);
                } else {
                    that.log(reply);
                }
            });

            for (let i = that.onConnectionResponse.length - 1; i >= 0; i--) {
                let cb = that.onConnectionResponse[i];
                if (cb(str) === true)
                    that.onConnectionResponse.splice(i, 1);
            }
        }).on('end', function (err) {
            that.log(`RCON connection ended!`);

            that.startRconConnection();

            for (let i = that.onConnectionEnd.length - 1; i >= 0; i--) {
                let cb = that.onConnectionEnd[i];
                if (cb(err) === true)
                    that.onConnectionEnd.splice(i, 1);
            }
        }).on('error', function (err) {
            that.log(`RCON errored with message: ${err}`);

            for (let i = that.onConnectionError.length - 1; i >= 0; i--) {
                let cb = that.onConnectionError[i];
                if (cb(err))
                    that.onConnectionError(i, 1);
            }
        });

        this.connection.connect();
    },

    startReceiver: function () {
        this.receiver = new logReceiver.LogReceiver({port: this.receiverPort});

        let that = this;
        this.receiver.on("data", function (data) {
            if (data.isValid) {
                // that.log(`Received LOG ${dataCount++}: ${data.message}`);

                redisC.rpush([REDIS_STAGING_KEY, that.ip + ':' + that.port + ' - ' + data.message], function (err, reply) {
                    if (err) {
                        that.log(err);
                    } else {
                        that.log(reply);
                    }
                });
                redisC.rpush([REDIS_KEY, that.ip + ':' + that.port + ' - ' + data.message], function (err, reply) {
                    if (err) {
                        that.log(err);
                    } else {
                        that.log(reply);
                    }
                });

                for (let i = that.onReceiverData.length - 1; i >= 0; i--) {
                    let cb = that.onReceiverData[i];
                    if (cb(data))
                        that.onReceiverData(i, 1);
                }
            }
        });
        this.receiver.on("invalid", function (err) {
            that.log(`Received invalid message: ${err}`);

            for (let i = that.onReceiverInvalid.length - 1; i >= 0; i--) {
                let cb = that.onReceiverInvalid[i];
                if (cb(err))
                    that.onReceiverInvalid(i, 1);
            }
        })
    },

    bindReceiver: function () {
        let that = this;
        setInterval(() => {
            this.execute(`logaddress_add ${LISTENING_IP}:${this.receiverPort}`, (res) => {
                that.log('Bound to receiver!');
            })
        }, 1000 * 60);
    },

    setHighDetails: function () {
        let that = this;
        setInterval(() => {
            that.execute('mp_logdetail 3', (res) => {
                sv.log(`Forcing 'mp_logdetail 3':  ${res}`);
            });
        }, 1000 * 60);
    },
    execute: function (command, callback) {
        if (this.authed) {
            this.syncExecute(command, callback);
        } else {
            this.onConnectionAuth.push(() => {
                this.syncExecute(command, callback);
                return true;
            });
        }
    },

    syncExecute: function (command, callback) {
        this.connection.send(command);

        this.onConnectionResponse.push((res) => {
            if (!util.isFunction(callback)) {
                console.log(callback);
            }
            callback(res);
            return true;
        })
    },

    log: function (message) {
        console.log(`${this.toString()} ${message}`);
    },

    toString: function () {
        return `[${this.name} (${this.ip}:${this.port})]`;
    }
};

function getServer(ip, port) {
    for (let i = 0; i < servers.length; i++) {
        if (servers[i].ip === ip && servers[i].port === port) {
            return servers[i];
        }
    }

    return undefined;
}

function response(res, message) {
    return JSON.stringify({
        error: false,
        message: message,
        response: res
    });
}

function log(message) {
    console.log(message);
}

function readServers() {
    let rawServers = fs.readFileSync('servers.json', {encoding: 'utf8'});
    let svs = JSON.parse(rawServers);
    for (let i = 0; i < svs['servers'].length; i++) {
        let obj = svs['servers'][i];
        let sv = new Server(obj['hostname'], obj['name'], obj['ip'], obj['port'], obj['password'], obj['receiverPort']);
        servers.push(sv);
        sv.startRconConnection();
        sv.startReceiver();
        sv.bindReceiver();
        sv.setHighDetails();
    }
}

/**********************
 *    STATIC CALLS    *
 **********************/

readServers();

/***************
 *    PAGES    *
 ***************/

// Used to log messages to the web-console
app.get('/consoleLog', (req, res) => {
    log('/consoleLog routed');
    log(req.query.message);
    res.send(response('Logged'));
});

app.get('/logs', (req, res) => {
    log('/logs routed');
    res.type('text');
    res.send(response(fs.readFileSync(LOGS_PATH, {encoding: 'utf8'})));
});

app.get('/logs_raw', (req, res) => {
    log('/logs_raw routed');
    res.type('text');
    res.send(fs.readFileSync(LOGS_PATH, {encoding: 'utf8'}));
});

app.get('/stdout', (req, res) => {
    log('/stdout routed');
    res.type('text');
    res.send(response(fs.readFileSync(STDOUT_PATH, {encoding: 'utf8'})));
});

app.get('/stderr', (req, res) => {
    log('/stderr routed');
    res.type('text');
    res.send(response(fs.readFileSync(STDERR_PATH, {encoding: 'utf8'})));
});

app.get('/kill', (req, res) => {
    log('/kill routed');
    res.type('text');
    process.exit(1);
    res.send('Killing this instance');
});


/*****************
 *    BINDING    *
 *****************/

app.listen(HTTP_PORT, () => {
    console.log('HTTP listening on ' + HTTP_PORT);
    console.log('Logging on: ' + LOGS_PATH);
    console.log('STDOUT on: ' + STDOUT_PATH);
    console.log('STDERR on: ' + STDERR_PATH);
});