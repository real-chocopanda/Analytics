<?php if (!defined('APPLICATION')) exit(); ?>
<h1><?php echo T($this->Data['Title']); ?></h1>
<div class="Info">
   <?php echo T($this->Data['PluginDescription']); ?>
</div>
<h3><?php echo T('Settings'); ?></h3>
<?php
   echo $this->Form->Open();
   echo $this->Form->Errors();
?>
<ul>
   <li><?php
      echo $this->Form->Label('Web ID', 'Plugin.Analytics.WebID');
      echo $this->Form->Textbox('Plugin.Analytics.WebID');
   ?></li>
   <li><?php
      echo $this->Form->Label('Domain', 'Plugin.Analytics.Domain');
      echo $this->Form->Textbox('Plugin.Analytics.Domain');
   ?></li>
</ul>
<?php
   echo $this->Form->Close('Save');
?>