<?php

namespace Iradi\Scripts;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Events
{
    public static function publishConfigs(PackageEvent $event)
    {
        $installedPackage = $event->getOperation()->getPackage();
        $homeDir = $event->getComposer()->getConfig()->get('home');

        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $files = glob($vendorDir, '/*iradi-settings/*');

        foreach ($files as $file) {
            $destPath = $homeDir.'/iradi-settings/'.basename($file);
            if(!file_exists($destPath)){
                copy($file, $destPath);
                print("Published File: {basename($file)}");
            } else {
                print("File already exists: {basename($file)}");
            }
        }
    }
    public static function publishConfigsPostUpdate(Event $event)
    {
        $homeDir = $event->getComposer()->getConfig()->get('home');

        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $files = glob($vendorDir, '/*iradi-settings/*');

        foreach ($files as $file) {
            $destPath = $homeDir.'/iradi-settings/'.basename($file);
            if(!file_exists($destPath)){
                copy($file, $destPath);
                print("Published File: {basename($file)}");
            } else {
                print("File already exists: {basename($file)}");
            }
        }
    }
}