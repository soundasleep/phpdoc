<?php

namespace PHPDoc2\Database;

/**
 * Represents an intelligent documentation database that can be
 * queried as necessary.
 */
interface Visible {

  /**
   * Get the HTML title for this page if generated.
   */
  function getTitle($options);

  /**
   * Get the filename for this page if generated.
   */
  function getFilename();

}
