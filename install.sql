-- SkyfallenUpdatesConsole SQL Installer
-- Skyfallen Developers
-- https://www.theskyfallen.com

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `appsettings` (
  `entry` int(11) NOT NULL,
  `appid` varchar(255) NOT NULL,
  `setting` varchar(255) NOT NULL,
  `settingvalue` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `importedapps` (
  `appid` varchar(255) NOT NULL,
  `appsecret` varchar(255) NOT NULL,
  `origin` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `appname` varchar(255) NOT NULL,
  `verified` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `packages` (
  `packageid` varchar(255) NOT NULL,
  `packagepath` varchar(255) NOT NULL,
  `packagename` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `appid` varchar(255) NOT NULL,
  `packagedate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `seeds` (
  `owner` varchar(255) NOT NULL,
  `appid` varchar(255) NOT NULL,
  `seed` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `versions` (
  `entry` int(11) NOT NULL,
  `appid` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `appsecret` varchar(255) NOT NULL,
  `vtype` varchar(255) NOT NULL,
  `seed` varchar(255) NOT NULL,
  `versionid` varchar(255) NOT NULL,
  `datapath` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL,
  `islatest` varchar(255) NOT NULL,
  `releasedate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `appsettings`
  ADD UNIQUE KEY `entry` (`entry`);

ALTER TABLE `importedapps`
  ADD UNIQUE KEY `appid` (`appid`);

ALTER TABLE `packages`
  ADD UNIQUE KEY `packageid` (`packageid`);

ALTER TABLE `seeds`
  ADD UNIQUE KEY `unique_seed` (`appid`,`seed`) USING BTREE;

ALTER TABLE `versions`
  ADD UNIQUE KEY `entry` (`entry`);

ALTER TABLE `appsettings`
  MODIFY `entry` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `versions`
  MODIFY `entry` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
