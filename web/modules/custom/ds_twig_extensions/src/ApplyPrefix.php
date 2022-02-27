<?php

namespace Drupal\ds_twig_extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ApplyPrefix extends AbstractExtension {

    public function getFilters() {
        return [
          new TwigFilter('apply_prefix', [$this, 'prefix_processing']),
        ];
    }

    public function prefix_processing($value) {
        // return the $value with prefix
        if (!empty($value)) {
          //dump($value);exit;
            return "Description: " . $value;
        }
        return NULL;
    }

}
