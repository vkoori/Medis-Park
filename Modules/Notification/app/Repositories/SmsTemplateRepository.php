<?php

namespace Modules\Notification\Repositories;

use App\Utils\Repository\BaseRepository;
use Modules\Notification\Enums\SmsTemplateEnum;
use Modules\Notification\Models\SmsTemplate;

/**
 * @extends BaseRepository<SmsTemplate>
 */
class SmsTemplateRepository extends BaseRepository
{
    public function __construct(private SmsTemplate $model) {}

    protected function getModel(): SmsTemplate
    {
        return $this->model;
    }

    public function getMessage(SmsTemplateEnum $template)
    {
        return $this->model
            ->query()
            ->where('template', $template)
            ->firstOrFail();
    }
}
