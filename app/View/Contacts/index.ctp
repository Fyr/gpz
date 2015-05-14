<style type="text/css">
#ContactIndexForm input[type=text], #ContactIndexForm textarea {
	width: 310px;
}
</style>
<?=$this->element('title', array('title' => $article['Page']['title']))?>
<div class="block">
	<?=$this->ArticleVars->body($article)?>
</div>

<?=$this->element('title', array('title' => __('Send message')));?>
<div class="block">
<?
	echo $this->Form->create('Contact');
	echo $this->Form->input('Contact.username', array('label' => array('text' => 'Ваше имя')));
	echo $this->Form->input('Contact.email', array('type' => 'text', 'label' => array('text' => 'Ваш e-mail для обратной связи')));
	echo $this->Form->input('Contact.body', array('type' => 'textarea', 'label' => array('text' => __('Your message'))));
	echo $this->Form->label(__('Spam protection'));
	echo $this->element('recaptcha');
	echo $this->Form->submit(__('Send'), array('div' => false, 'class' => 'submit'));
	echo $this->Form->end();
?>
</div>