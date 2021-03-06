# Changelog

## [Unreleased]
### Added
- cache size display added

### Changed
- 

### Fixed
- 


## [2.3.0] - 2019-07-14
### Added
- add self-updater
### Changed
- update CHANGELOG.md
- update VERSION



## [2.2.9] - 2019-07-14
### Changed
- update font-awesome
- update CHANGELOG.md
- update VERSION

###Fixed
- fix font-awesome 404 after update
- fix articlestatus error while array ist empty



## [2.2.8] - 2019-06-30
### Changed
- update ajax.php, index.php, info.php
- update composer component font-awesome
- cleanup update.json
- update CHANGELOG.md
- update VERSION



## [2.2.7] - 2019-05-30
### Changed
- change "synchonisieren" to "synchronisieren" (Muhmann)



## [2.2.6] - 2019-05-12
### Added
- help for articlestatus tool added
### Changed
- change backlog display



## [2.2.5] - 2019-05-08
### Added
- btn sync the all added in tool articlestatus
### Fixed
- sync fixed at tool articlestatus



## [2.2.4] - 2019-05-06
### Added
- tool articlestatus added
- tool ordersync datatable added
### Fixed
- tool articlestatus sync fix



## [2.2.3] - 2019-03-18
### Changed
- change buttons page



## [2.2.2] - 2019-03-17
### Added
- new buttons added
### Changed
- files moved
- change .gitignore
### Fixed
- fix install issue



## [2.2.1] - 2019-03-14
### Added
- tool missing images has now a sync button
### Changed
- update help
- limit for order sync list to 50 changed
### Fixed
- fix nav dropdown-menue issue
- code-style fix
- fix responsive issue on dashboard
- fix responsive issue on connector button page



## [2.2.0] - 2019-03-08
### Added
- cli commandlist added
- plenty commandlist added
- php_cs added
### Changed
- optimization code-style via php-cs-fixer



## [2.1.0] - 2019-03-03
### Changed
- counted backlog now loading via ajax
### Fixed
- counted backlog over 3500 was not displayed
- error while execute cli command with function popen
- log overflow (max. filesize ~100KB)



## [2.0.6] - 2019-02-16
### Added
- tool order sync added

### Changed
- dropdown menue expanded by hover
- update bootstrap



## [2.0.5] - 2019-02-15
### Changed
- update overlayer ux



## [2.0.4] - 2019-02-11
### Changed
- connector button thumbnail: cleanup removed. button theme: cache: generate added



## [2.0.3] - 2019-02-10
### Added
- added an overlay while connector is executing an command
- update info added
### Fixed
- fix issue #6 (bvogel) singlesync isn't working
- bugfix overlay



## [2.0.2] - 2019-02-6
### Changed
- update update vendor twbs/bootstap
- delete vendor mobiledetect/mobiledetectlib
- delete controller action tools/info
- update VERSION
- update README.md
- update CHANGELOG.md
- delete module tools_info
- delete module beta_info
- delete prefix sw: from buttons



## [2.0.1] - 2018-12-05
### Changed
- changed phpinfo(): send header to disable HSTS, add link to open phpinfo in a new tab
- change article sync from article-id to variantennummer
- update README.md extended with information about the folder name
- change VERSION to 2.0.1
### Fixed
- fix error: popen() has been disabled for security reasons
### Security
- brute force check added



## [2.0.0-beta1] - 2018-12-05
Initial upload
