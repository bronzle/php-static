<?php
function send_email($template_name, $subject, $name, $from_email, $variables = array()) {
  if (has_config('mail_settings', array('to_email', 'from_email'), true)) {
    return mail(
      (is_array(config('mail_settings.to_email')) ? implode(', ', config('mail_settings.to_email')) : config('mail_settings.to_email')),
      $subject,
      render('contact', array_merge(
        array('subject' => $subject),
        $variables
      ), config('emails_root'), false, false, false),
      implode("\r\n", array(
        'From:' . $name . '<' . config('mail_settings.from_email') . '>',
        'Reply-To:' . $name . '<' . $from_email . '>',
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=iso-8859-1'
      )) . "\r\n", null
    ) ? true : false;
  }
}
