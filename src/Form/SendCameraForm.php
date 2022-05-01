<?php

namespace Drupal\camera_widget\Form;

use Drupal\Component\Render\PlainTextOutput;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\camera_widget\Ajax\SendCameraCommand;
use Symfony\Component\HttpFoundation\Request;

class SendCameraForm extends ConfigFormBase {
    /**
     * Save Signature.
     */
     public static function saveCamera(string $selector, Request $request) : AjaxResponse {
        $encoded_image = $request->request->get('camera');
        $url = '';
        if (!empty($encoded_image)) {
          // Create image directory.
          $file_directory = trim($request->request->get("file_directory"), '/');
          $destination = PlainTextOutput::renderFromHtml(\Drupal::token()->replace($file_directory, []));
          $uri = \Drupal::config('system.file')->get('default_scheme') . '://';
          if (!empty($destination)) {
            $uri .= $destination . '/';
            $path = \Drupal::service('file_system')->realpath($uri);
            $dir = \Drupal::service('file_system')
              ->prepareDirectory($path, FileSystemInterface::CREATE_DIRECTORY);
          }
          // Convert image base64 to file.
          $encoded_image = explode(",", $encoded_image)[1];
          $encoded_image = str_replace(' ', '+', $encoded_image);
          $decoded_image = base64_decode($encoded_image);
          $filename = date('ymd') . '_' . rand(1000, 9999) . '.png';
          // Saves a file to the specified destination and creates a database entry.
          $file = file_save_data($decoded_image, $uri . $filename, FileSystemInterface::EXISTS_REPLACE);
          $uri = $file->getFileUri();
          $url = Url::fromUri(file_create_url($uri))->toString();
          [$width, $height] = getimagesize($uri);
    
          $entity_type = $request->request->get("entity_type");
          $entityLoad = \Drupal::entityTypeManager()->getStorage($entity_type);
    
          $id = $request->request->get("entity_id");
          $entity = $entityLoad->load($id);
    
          $field_name = $request->request->get("field_name");
          /*
           * Reserved for future feature.
          $delta = $request->request->get("delta");
          $bundle = $request->request->get("bundle");
           */
          $value = [
            'target_id' => $file->id(),
            'width' => $width,
            'height' => $height,
          ];
          $entity->$field_name->appendItem($value);
          $entity->save();
        }
        if (!empty($url)) {
          $response = new AjaxResponse();
          $response->addCommand(new SendCameraCommand($selector, $url));
        }
    
        return $response;
     }

     public function getFormId() {
         return 'camera_widget_sendCamera_form';
     }
}