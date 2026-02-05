# TYPO3 Extension cri_datwrapper

## 1 Features

* Datawrapper Graphics can be created as a file in the TYPO3 file list


## 2 Usage

### 2.1 Installation

#### Installation using Composer

The recommended way to install the extension is using Composer.

Run the following command within your [Composer][1] based TYPO3 project:
Or install via extension manager

```
composer require cri/cri_datawrapper
```

### 2.2 Hints

#### Output

For the output, the HTML is used directly from [Datawrapper].

#### SQL changes

In order not to have to access the oEmbed interface permanently, four fields are added to the sys_file_metadata table

## 3 Administration corner

### 3.1 Versions and support

| Cri_Datawrapper | TYPO3       | PHP       | Support / Development                   |
|------------------|------------| ----------|---------------------------------------- |
| 1.x              | 12.x- 13.4 | > 8.0     | Initial, experimental release           |

### 3.2 Contribution

**Pull Requests** are gladly welcome! Nevertheless please don't forget to add an issue and connect it to your pull
requests. This is very helpful to understand what kind of issue the **PR** is going to solve.

**Bugfixes**: Please describe what kind of bug your fix solve and give us feedback how to reproduce the issue. We're
going to accept only bugfixes if we can reproduce the issue.
