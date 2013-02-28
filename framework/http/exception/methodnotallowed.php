<?php
/**
 * @package     Koowa_Http
 * @subpackage  Exception
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Http Exception Bad Request Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Http
 * @subpackage  Exception
 */
class KHttpExceptionMethodNotAllowed extends KHttpExceptionAbstract
{
    protected $code = KHttpResponse::METHOD_NOT_ALLOWED;
}