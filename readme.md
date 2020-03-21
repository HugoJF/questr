# Questr

Quest system for CS:GO servers without dependencies with a in-game skin shop based on `kgns/weapons` plugin.

## How it works

This system is based on the internal UDP log system present in the Source engine.

A daemon is used to ensure configured servers have the needed *convars* for this system to work. The daemon is also used to collect each UDP packet coming from the servers, process them and store in a Redis queue.

Once the log packets are processed and stored, a job, scheduled to run each minute, is dispatched to compute and update individual quest progresses.

Quests types are implemented with special classes that define how and which events (coming from the CS:GO servers) are processed into quest progresses. Quest modifiers can also be implemented in order to increase quest variety and difficulty.

Quests are count based (kill count, damage count, etc) and have a reward once completed. They can also have a deadline and entry cost.

Quests are created by the administrator manually, and once available, must be individually started by users (even quests without entry costs).

## Project status

This project is mostly abandoned since it needed some serious rework on the main quest processing job to avoid unecessary database load. Since then, [CSGO:Pipeline](https://github.com/HugoJF/csgo-pipeline) was fully implemented as a centralized CS:GO event processing system.

Since this project did not get much usage from my servers, it never received an update to work with CS:GO Pipeline, which would solve part of the performance problems.

## Screenshots

#### Homepage

<p align="center">
    <img src="https://i.imgur.com/ypDA0wC.png"/>
</p>

#### Quest list 

<p align="center">
    <img src="https://i.imgur.com/WfIuolD.png"/>
</p>

#### Rank 
<p align="center">
    <img src="https://i.imgur.com/Y4rV2fv.png"/>
</p>

#### Shop 
<p align="center">
    <img src="https://i.imgur.com/gTkPphM.png"/>
</p>

#### Shop Filter 
<p align="center">
    <img src="https://i.imgur.com/kkjkRdz.png"/>
</p>

#### Shop buy screen
<p align="center">
    <img src="https://i.imgur.com/fSlIOXY.png"/>
</p>

#### Inventory 
<p align="center">
    <img src="https://i.imgur.com/iUsBoaV.png"/>
</p>

## Requirements
  - PHP 7.x
  - NodeJS
  - MySQL/MariaDB
  - Redis
  - CS:GO server
