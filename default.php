<?php if (!defined('APPLICATION')) die();


//
// Here's the info about my meager plugin
//
$PluginInfo['Analytics'] = array
(
  'Name' => 'Analytics',
  'Description' => 'Inserts Google Analytics Javascript EVERYWHERE!',
  'Version' => '1.0',
  'SettingsUrl' => '/plugin/analytics',
  'Author' => 'Lykaon',
  'AuthorEmail' => 'lykaon@strahotksi.com',
  'AuthorUrl' => 'http://www.strahotski.com',
);

//
// Did I mention this was a plugin?  That would explain our desire to
// extend Gdn_Plugin. How else would we be an uber-plugin of hawtness?
//
class analyticsPlugin extends Gdn_Plugin
{

  /**
   * Add Google Analytics js script to the page head
   * It uses a WebId (ex 'UA-114584654-1') and a Domain (ex '.foobar.com')
   * @param Controller $Sender
   */
  public function Base_Render_Before($Sender)
  {
    $Sender->Head->AddString
    (
      "<script type=\"text/javascript\">var _gaq = _gaq || [];_gaq.push(['_setAccount', '" . Gdn::Config('Plugin.Analytics.WebID') . "']);_gaq.push(['_setDomainName', '" . Gdn::Config('Plugin.Analytics.Domain') . "']);_gaq.push(['_trackPageview']);(function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();
</script>"
    );
  }

  /**
   * Backend: Make the route to the plugin settings page available
   * @param Controller $Sender
   */
  public function PluginController_Analytics_Create($Sender) {

  	$Sender->Title('Analytics');
  	$Sender->AddSideMenu('plugin/analytics');

  	// If your sub-pages use forms, this is a good place to get it ready
    $Sender->Form = new Gdn_Form();

    $this->Dispatch($Sender, $Sender->RequestArgs);
  }

  	/**
   	 * Backend: Controller of the plugin settings page
   	 * @see Gdn_Plugin::Controller_Index()
   	 */
	public function Controller_Index($Sender) {
      // Prevent non-admins from accessing this page
      $Sender->Permission('Vanilla.Settings.Manage');
      $Sender->SetData('PluginDescription',$this->GetPluginKey('Description'));

      $Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      $ConfigurationModel->SetField(array(
         'Plugin.Analytics.WebID'     => ''
      ));
      $ConfigurationModel->SetField(array(
         'Plugin.Analytics.Domain'     => ''
      ));

      // Set the model on the form.
      $Sender->Form->SetModel($ConfigurationModel);

      // If seeing the form for the first time...
      if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
         // Apply the config settings to the form.
         $Sender->Form->SetData($ConfigurationModel->Data);
      } else {
         $Validation->ApplyRule('Plugin.Analytics.WebID', 'string');
         $Validation->ApplyRule('Plugin.Analytics.WebID', 'Required', T('ValidateRequired'));
         $Validation->ApplyRule('Plugin.Analytics.Domain', 'string');
         $Validation->ApplyRule('Plugin.Analytics.Domain', 'Required', T('ValidateRequired'));

         $Saved = $Sender->Form->Save();
         if ($Saved) {
            $Sender->StatusMessage = T("Your changes have been saved.");
         }
      }

      // GetView() looks for files inside plugins/PluginFolderName/views/ and returns their full path. Useful!
      $Sender->Render($this->GetView('settings.php'));
   }


  //
  // Here's where we tell Garden what to do to set us up the bomb.  But
  // we're just simpletons so we have no setup.  Oh to be smart...
  //
  public function Setup()
  {
    // Nothing to do here!
  }
}
