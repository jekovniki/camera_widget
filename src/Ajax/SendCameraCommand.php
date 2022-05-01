<?php

namespace Drupal\camera_widget\Ajax;

use Drupal\Core\Ajax\CommandInterface;

/**
 * Ajax command to send an image base64 field formatter.
 */

 class SendCameraCommand implements CommandInterface {
     /**
      * The ID for the camera element.
      *
      * @var string
      */
    protected $selector;

      /**
       * The url for the camera element.
       * 
       * @var string
       */
    protected $cameraSrc;

    /**
     * Constructor.
     * 
     * @param string $selector
     * The ID for the camera element;
     * @param string $camera_src
     * The url camera element.
     */

     public function __construct($selector, $camera_src) {
         $this->selector = $selector;
         $this->cameraSrc = $camera_src;
     }

     /**
      * Implements Drupal\Core\Ajax\CommandInterface:render().
      */
      public function render() {
          return [
              'command' => 'SendCamera',
              'selector' => $this->selector,
              'camera_src' => $this->cameraSrc,
          ];
      }
 }