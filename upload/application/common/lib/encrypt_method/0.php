<?php
$return = md5(md5($password).$randstr.config('sys.safecode'));