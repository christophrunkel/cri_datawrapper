<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "ayacoo_soundcloud".
 *
 * Auto generated 20-09-2023 14:48
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Datawrapper online media helper',
  'description' => NULL,
  'category' => 'plugin',
  'version' => '1.0.0',
  'state' => 'stable',
  'uploadfolder' => false,
  'clearcacheonload' => false,
  'author' => 'Christoph Runkel',
  'autoload' > [
        'psr-4'=> [ 'Cri\\CriDatawrapper\\' => 'Classes', ]
        
   ],
  'author_email' => 'dialog@christophrunkel.de',
  'author_company' => NULL,
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '11.5.0-12.5.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
);

