<?php

namespace Biigle\Modules\Maia\Notifications;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobStateChanged extends Notification
{
    /**
     * The job which changed state.
     *
     * @var MaiaJob
     */
    protected $job;

    /**
     * Create a new notification instance.
     *
     * @param MaiaJob $job
     * @return void
     */
    public function __construct(MaiaJob $job)
    {
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $settings = config('maia.notifications.default_settings');

        if (config('maia.notifications.allow_user_settings') === true) {
            $settings = $notifiable->getSettings('maia_notifications', $settings);
        }

        if ($settings === 'web') {
            return ['database'];
        }

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject($this->getTitle($this->job))
            ->line($this->getMessage($this->job));

        if (config('app.url')) {
            $message = $message->action('Show job', route('maia', $this->job->id));
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $array = [
            'title' => $this->getTitle($this->job),
            'message' => $this->getMessage($this->job),
        ];

        if (config('app.url')) {
            $array['action'] = 'Show job';
            $array['actionLink'] = route('maia', $this->job->id);
        }

        return $array;
    }

    /**
     * Get the title for the state change.
     *
     * @param MaiaJob $job
     * @return string
     */
    protected function getTitle($job)
    {
        return 'MAIA job state changed';
    }

    /**
     * Get the message for the state change.
     *
     * @param MaiaJob $job
     * @return string
     */
    protected function getMessage($job)
    {
        return 'The state of the MAIA job has changed';
    }
}
