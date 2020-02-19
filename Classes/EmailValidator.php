<?php
declare(strict_types=1);
namespace Highline\SendGrid\EmailValidator;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Validation\Validator\AbstractValidator;

class EmailValidator extends AbstractValidator
{
    /**
     * @var SendGrid\SendGridService
     * @Flow\Inject
     */
    protected $sendGridService;

    /**
     * @param mixed $value
     * @throws Exception
     */
    public function isValid($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->addError('Please specify a valid email address.', 1581746584);
            return;
        }

        $result = $this->sendGridService->validateEmail($value);

        if ($result['verdict'] === 'Risky' || $result['verdict'] === 'Invalid') {
            if (isset($result['suggestion'])) {
                $suggestedEmail = $result['local'] . '@' . $result['suggestion'];
                $this->addError('Did you mean %1$s', 1581746055, [$suggestedEmail]);
            } else {
                $this->addError('Please specify a valid email address.', 1581746584);
            }
        }
    }
}