<?php
/**
 * Minimal wrapper used when a component is rendered outside the full
 * template (tp=1 print-friendly / raw component requests).
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
$doc->addStyleSheet('https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template.css', ['version' => 'auto']);
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="head" />
</head>
<body class="fd-body fd-component-only">
    <div class="container py-4">
        <jdoc:include type="message" />
        <jdoc:include type="component" />
    </div>
</body>
</html>
