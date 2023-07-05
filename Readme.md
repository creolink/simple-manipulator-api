# Simple Image Manipulator API

## Prerequisites
 - installed docker (ex: docker desktop) 
 - for local development, installed composer
   production should be fully configured in docker container


## Setup
 - use `make` command to get help
 - use `make prod` to setup prod environment 
   - this will copy application to docker container
 - use `make up` to setup dev environment
   - this will allow to change code and see results


## Configuration
 - full framework configuration exists in `config` folder 
 - in `config.php` is general framework configuration
 - in `imagemagick.config.php` is configuration required for usage of ImageMagic library
 - in `gd.config.php` is configuration contains config for gd library
 - libraries can be switched, in `config.php`
 - original images exist in `public/resources/` folder (if you would like to have more, please add them there)
 - modified images are stored in `tmp/` folder


## How to use the service
  ImageMagic:
  - resize:width,height,blur,bestFitFlag,filter
      mandatory: width (integer), height (integer)
      optional: blur(float, defaults to 1), bestFitFlag (boolean, defaults to false), filter (depends on https://www.php.net/manual/en/imagick.constants.php#imagick.constants.filters)
      example: /dog.jpg/resize:200,400,100,true,FILTER_LANCZOS
  - crop:width,height,startX,startY
      mandatory: width (integer), height (integer)
      optional: startX (integer, defaults to 0), startY (integer, defaults to 0)
      example: /dog.jpg/crop:200,400
  - combine manipulators
    /dog.jpg/crop:width,height/resize:width,height/crop:width,height
  - manipulators with invalid structure are ignored
    (additional level of validation can be added in code)
  - not implemented validators throws an exception
  - url without modyficators will result in image not found exception
    example: /dog.jpg/
  - other manipulators are not programmed but application allows to extend solution
  - other libraries are not handled
  - only webp, jpg, gif and png files are handled
  - Exif / image properties are not preserved (I know it was a requirement, but I am already after deadline)


## Questions to ask
 - how modification parameters should be separated? 
    ex: /dog.webp/crop?width,height,x,y
    ex: /dog.webp/resize?width,height
    ex: /dog.webp/resize_width_height/crop_width_height_x_y/
    > initially I assumed many combinations
 - should there be a possibility to upload an image first? or the images exist already on the server?
    > I didn't implement any upload possibility. The original images must exist already in `public/resources/` folder.
 - how options should be modelled?
    for example Imagick::resizeImage has $filterType, $blur, $bestFit parameters and PHP GD imagecopyresampled doesn't
    should we have possibility to use these additional filters? or only change the size?
    > I've assumed, yes, additional filters should be used
 - if there is unknown modificator in url, should it be ignored or should application throw an exception?
    > Initially I have assumed, app throws an exception.
 - should url without modification parameters return original image?
    > initially I assumed, no


 ## Documentation used
  - https://www.php.net/manual/en/class.imagick.php
  - https://www.php.net/manual/en/ref.image.php


## Other useful things
 - to remove docker cache use: `docker builder prune`
 - to run php-cs-fixer execute: `tools/php-cs-fixer/vendor/bin/php-cs-fixer fix app --rules=@Symfony`

## Tests
 - get into container with `make php`
 - and run tests with `vendor/bin/phpunit`

