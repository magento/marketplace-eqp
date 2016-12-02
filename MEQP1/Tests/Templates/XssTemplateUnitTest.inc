<!--unsafe-->

<?php $block->getSomeData(); echo $block->getSomeData(); echo $block->getSomeData();?>
<?=  $block->getTitle();?>
<?php echo $object->getSomeMethod($block->getId());?>
<?php echo "Title: $title";?>
<?php echo $block->escapeUrl($var) . $var . 'bla';?>
<?php echo $var;?>
<?= $someVar?>
<?php echo __("bla");?>

<?php echo $object->escapeHtml() .
    $var .
    $block->getSomeData();
?>
<?php echo '\'' . $code . '\',\'' . $block->quoteEscape($data['parentSymbol']) . '\''; ?>
<?php echo "\"" . $code . "\",\"" . $block->quoteEscape($data['parentSymbol']) . "\""; ?>
<?php echo '\'' . $code . '\',\'' . $block->escapeQuote($data['parentSymbol']) . '\''; ?>
<?php echo "\"" . $code . "\",\"" . $block->escapeQuote($data['parentSymbol']) . "\""; ?>
<?php echo $res = $var . $tt . $var ? 1 : 2 ? $var . striptags($var) : 5; ?>
<?php echo $res = $var . $tt . $var ? 1 : 2 ? $block->getExtendedElement($switchAttributeCode)->print() : 5; ?>
<?php echo $res = $var . $var > 5 ? ($tt . $var ? 1 : 2 ? $block->getId($id)->print() : count($tt)) : 5; ?>
<?php print $var;?>
<?php echo <<<END
Some text goes here $variable.
END;
?>
<?php echo ($my_false) . (($c) ? $block->escapeUrl( $block->my_funct() . $var) : (false) ? sizeof ($var) :'bla bla'); ?>
<?php /* @noEscape */ echo $code; ?>
<?php /* @noEscape */ echo '\'' . $code . '\',\'' . $block->escapeQuote($data['parentSymbol']) . '\''; ?>


<!--safe-->
<input name="form_key" type="hidden" value="<?php echo $block->escapeUrl($var) ?>" />
<input name="form_key" type="hidden" value="<?php echo $block->jsQuoteEscape('bla') ?>" />
<input name="form_key" type="hidden" value="<?php echo $block->urlEscape("bla bla") ?>" />
echo $var;
<?php echo $block->getId("bla bla") ?>
<?php echo $block->stripTags("bla bla") ?>
<?= $block->quoteEscape("bla bla"); ?>
<?php echo "\$var1"; ?>
<?php echo '$var'; ?>
<?php echo "bla"; ?>
<?php echo 'bla'; ?>
<?php echo (real)$var;?>
<?php echo (int)$var; ?>
<?php echo (bool)$var; ?>
<?php echo count($var); ?>
<?php echo htmlspecialchars($var); ?>
<?php echo $object->htmlEscape(); ?>
<?php pecho($var);?>
<?php echo $object->escapeHtml() .
    (bool)$var .
    htmlspecialchars("bla bla");
?>
<?= $block->getExtendedElement($switchAttributeCode)->getId(); ?>
<?= $block->escapeHtml($_filter->getFilter()->getClearLinkText()) ?>
<?php echo ($card->default) ? $block->escapeHtml(__('Yes')) : $block->escapeHtml(__('No')); ?>
<?php echo $res = $var . $tt . $var ? 1 : 2 ? $block->getExtendedElement($switchAttributeCode)->getId() : 5; ?>
<?php echo $var % 2; ?>
<?php echo $var ** 2;?>
