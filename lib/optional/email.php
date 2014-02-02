<?php
function send_email($template_name, $subject, $name, $from_email, $variables, $message) {
  if (has_config('mail_settings', array('to_email', 'from_email'), true)) {
    $template_name = config('root_dir') . '/' . config('emails_root') . '/' . $template_name . '.php';
    if (file_exists($template_name)) {
      $mail_content = get_template_contents($template_name, array(
        'subject' => $subject,
        'variables' => $variables,
        'message' => nl2br($message)
      ));
      return mail(
        (is_array(config('mail_settings.to_email')) ? implode(', ', config('mail_settings.to_email')) : config('mail_settings.to_email')),
        $subject,
        $mail_content,
        implode("\r\n", array(
          'From:' . $name . '<' . config('mail_settings.from_email') . '>',
          'Reply-To:' . $name . '<' . $from_email . '>',
          'MIME-Version: 1.0',
          'Content-type: text/html; charset=iso-8859-1'
        )) . "\r\n"
      );
    } else {
      throw new MissingTemplate(MissingTemplate::OTHER, $template_name);
    }
  }
}
