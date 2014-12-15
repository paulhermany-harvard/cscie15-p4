# Project 4 - Configurely

## Live URL
<http://configurely.com>
(<http://p4.paulhermany.me>)


## Description
A web-based application catalog and configuration management tool.

.. an application catalog
is a list of software packages (e.g. web applications). Frequently used in conjunction with a service management system, an application catalog serves as a both a software manifest for IT support staff as well as a index of technical resources for end users.
.. configuration management tool
is a software system for managing application settings. A centralized system for securely storing and maintaining application settings can alleviate common problems with change management.

* An application is any type of configurable software package. Add web or desktop applications, vended products, mobile apps, cron jobs, script packages, etc.
* A configuration is simply a named container for your application's settings. Typically this will correspond with the application environment such as development, stage, production, etc.
* A setting is simply a key/value pair, but you can store pretty much anything including strings, integers, boolean values, and files.

## Demo
To be provided during the Tuesday virtual section.

## Instructions
No special instructions for this project.

## Third-party plug-ins/libraries

This app uses the Laravel framework.

| Name               | Version | Url                                   |
| ------------------ | ------- | ------------------------------------- |
| Bootstrap          | 3.2.0   | http://getbootstrap.com/              |
| BootstrapValidator | 0.5.2   | http://bootstrapvalidator.com/        |
| D3JS               | 3.5.2   | http://d3js.org/                      |
| Faker              | 1.5.0   | https://github.com/fzaninotto/Faker   |
| Html5Shiv          | 3.7.2   | https://code.google.com/p/html5shiv/  |
| JQuery             | 1.11.1  | http://jquery.com/                    |
| Respond            | 1.4.2   | https://github.com/scottjehl/Respond/ |

### Implementation details

* Bootstrap

  This website uses Bootstrap with the default theme to provide a responsive user interface that works on mobile and desktop.

* Bootstrap Validator

  This website uses Bootstrap Validator for client-side form validation.

* D3JS

  This website uses the D3JS visualization library for generating the SVG chart used on the homepage.
  
* Faker

  This website uses the Faker library to generate random snippets of data for the homepage visualization (for guest users)
  
* Html5Shiv

  This website uses Html5Shiv (included only by CDN) to shim the html for browsers that do not support Html5.

* JQuery

  This is a dependency for the Bootstrap and Bootstrap Validator library.

* Respond

  This website uses Respond (included only by CDN) to shim the css for browsers that do not support CSS3 or responsive css rules.