<?php
// This line is not okay... just!
if (($reallyLongVarName === true) || (is_array($anotherLongVarName) === false)) {
    // Do something.
}


// This line is is too long.
if (($anotherReallyLongVarName === true) || (is_array($anotherReallyLongVarName) === false) || count($anotherReallyLongVarName) > 0) {
    // Do something.
}
$str="This is another really long comment that is going to go well over 100 characters, with no closing php tag after it.";

__("This is another really long comment that is going to go well over 100 characters, with no closing php tag after it.");
