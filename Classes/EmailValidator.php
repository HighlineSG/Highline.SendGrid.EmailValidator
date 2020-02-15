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
        $result = $this->sendGridService->validateEmail($value);

        if ($result['verdict'] === 'Risky' || $result['verdict'] === 'Invalid') {
            if (isset($result['suggestion'])) {
                $this->addError('Did you mean ' . $result['local'] . '@' . $result['suggestion'], 1581746055);
            } else {
                $this->addError('Please specify a valid email address.', 1581746584);
            }
        }
    }
}