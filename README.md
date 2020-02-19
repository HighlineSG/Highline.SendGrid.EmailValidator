# Highline.SendGrid.EmailValidator
EmailValidator for Flow Applications using the [SendGrid Email Validation API](https://sendgrid.com/solutions/email-validation-api/).

## Installation

```
composer require highline/sendgrid-emailvalidator
```

## Configuration

After successful installation make sure to configure the SendGrid API key in the Settings.yaml:

```yaml
Highline:
  SendGrid:
    EmailValidator:
      apiKey: '<SENDGRID_API_KEY>'
```

## Usage

The validator can be used like any other validator inside your Flow Application using the @Flow\Validate annotation:
```yaml
@Flow\Validate(type="Highline\SendGrid\EmailValidator\EmailValidator")
```
