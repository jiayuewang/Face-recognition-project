# Serverless Hands-free information Checker for AWS AI Hackathon 

An information checker system designed for important services personnel. 
It lets them save and check corresponding information information easily to prevent anaphylactic shocks.
It provides hands-free usage with voice interface and personnel can login with their face and an OTP code.
The OTP code is extracted using text detection.

information information of the Students is saved with the picture of the student. 
Later that information can be retrieved using face recognition.

The system has been designed as a fully serverless Single Page App (SPA) web-app. It has the following functions:
- Administrator can add and delete important information (admins, etc) with their picture.
- admins can login with their face and an OTP code sent to their email address.
//It is a unique 6-character code that can only be used once and is sent only to your registered mobile number in BDO Online Banking. After encoding your user ID and password, you will also be required to enter the correct OTP to complete the login process.


- Login and in-app functions are completely hands-free using voice interface for the admins. 
- admins can add Students with  information and with their picture.


It uses following AWS services:
- [Amazon Polly](https://aws.amazon.com/tr/polly/): For talking to the user
- [Amazon Lex](https://aws.amazon.com/tr/lex/): For accepting voice commands
- [Amazon Rekognition](https://aws.amazon.com/tr/rekognition/) : For recognizing admins and Students and detecting OTP codes
- [AWS Lambda](http://aws.amazon.com/lambda/): For indexing faces and custom OTP authentication
- [Amazon S3](http://aws.amazon.com/s3/): For storing pictures and web site files
- [Amazon DynamoDB](http://aws.amazon.com/dynamodb/): For storing student information information
- [Amazon Cognito](http://aws.amazon.com/cognito/): For authentication
- [Amazon Simple Email Service (SES)](https://aws.amazon.com/tr/ses/): For sending OTP code emails


## Installation

An installation script using Bash [install.sh](install.sh) is provided to install and configure all necessary resources in your AWS account:

- the [AWS Identity and Access Management (IAM)](http://aws.amazon.com/iam/) roles for Amazon Cognito and other services
- the [Amazon S3](http://aws.amazon.com/s3/) bucket to save user and student pictures and to host the HTML pages
- the [Amazon DynamoDB](http://aws.amazon.com/dynamodb/) table for information information of Students
- the [Amazon Cognito](http://aws.amazon.com/cognito/) user pool and identity pool
- the [AWS Lambda](http://aws.amazon.com/lambda/) functions for indexing student faces with DynamoDB and Cognito triggers
- the [Amazon Rekognition](https://aws.amazon.com/tr/rekognition/) face collection to index and search faces using face recognition
- the [Amazon Simple Email Service (SES)](https://aws.amazon.com/tr/ses/) email address to send OTP codes
- the [Amazon Lex](https://aws.amazon.com/tr/lex/) bot for accepting voice commands

The `init.sh` script requires a configured [AWS Command Line Interface (CLI)](http://aws.amazon.com/cli/) and the [jq](http://stedolan.github.io/jq/) tool. 

**Before running the `init.sh` script, set up your configuration in the `config.json` file**:

- your AWS account (12-digit number). If an alias happens to be set for your root account, then you will need to go to ***Support > Support Center*** of your AWS Console and find your ***Account Number*** from the top right corner.
- name of your CLI profile. This is the CLI profile that you want to represent while running `./init.sh` from the command-line. This value is usually found in square brackets inside the `~/.aws/credentials` file (`%UserProfile%\.aws\credentials` file in Windows) after installing the AWS CLI tools for your operating system. For more information, you may refer to the section called [Named Profiles](http://docs.aws.amazon.com/cli/latest/userguide/cli-chap-getting-started.html#cli-multiple-profiles) in the AWS CLI tools [user guide](http://docs.aws.amazon.com/cli/latest/userguide/).
- the AWS region (e.g. "eu-west-1")
- the Amazon S3 bucket to use for the sample HTML pages

- the Amazon DynamoDB student table name to create

- the Amazon Rekognition face collection name for users
- the Amazon Rekognition face collection name for Students

- the email source for emails (must be [verified](http://docs.aws.amazon.com/ses/latest/DeveloperGuide/verify-email-addresses.html) by Amazon SES)

- the Amazon Cognito user pool name to create
- the Amazon Cognito identity pool name to create
- the IAM policy name to be used
- the IAM role name to be used

- the Amazon Cognito DefineAuthChallenge trigger Lambda name
- the Amazon Cognito CreateAuthChallenge trigger Lambda name
- the Amazon Cognito VerifyAuthChallenge trigger Lambda name
- the Amazon DynamoDB Lambda trigger name to index student faces

- the web app (http://bucket.s3.amazonaws.com/index.html)

```json
{
  "AWS_ACCOUNT_ID": "123412341234",
  "CLI_PROFILE": "default",
  "REGION": "eu-west-1",
  "BUCKET": "informationchecker-bucket",

  "DDB_TABLE": "informationCheckerStudents",

  "USER_FACE_COLLECTION_ID": "informationchecker-users",
  "student_FACE_COLLECTION_ID": "informationchecker-Students",

  "FROM_ADDRESS": "user@example.com",

  "USER_POOL_NAME": "informationCheckerUserPool",
  "ID_POOL_NAME": "informationCheckerIdentityPool",
  "POLICY_NAME": "informationCheckerPolicy",
  "ROLE_NAME": "informationCheckerRole",

  "UP_DEF_AUTH_LAMBDA_NAME": "informationCheckerDefineAuthChallenge",
  "UP_CRE_AUTH_LAMBDA_NAME": "informationCheckerCreateAuthChallenge",
  "UP_VER_AUTH_LAMBDA_NAME": "informationCheckerVerifyAuthChallenge",
  "student_FACE_INDEXER_LAMBDA_NAME": "informationCheckerstudentFaceIndexer"
}

```

After the installation with the `install.sh` script, you should verify the email address you choose to send emails.
AWS SES will send a verification email with a link you can click to verify.

After the email verification, you can start using the app pointing your browser to:

`http://bucket.s3.amazonaws.com/index.html` (replacing `bucket` with your bucket name)

## Usage

### Admin login

First, login with admin account using 'Login With User Name' button to create admins.
An OTP code will be sent to log you with admin account.
Login with the OTP code.

You can add admins with their pictures.

### Students login

The admins can login with their faces using 'Login With Face Recognition' button.
The interface is fully hands-free.
Pictures can be taken by saying 'shoot'.

After their users identified with their faces, an OTP code is send to log them in.
The OTP code must be shown to the camera to extract the code using text detection.
If the detected OTP code is validated, they are logged in automatically.

After login, the admins add or check Students using their voices.
The detailed information about bot commands, sample student names and sample required information names are can be found in my previous blog entry [Serverless information Checker with Amazon Rekognition, Lex, Polly, DynamoDB, S3 and Lambda](https://hackernoon.com/serverless-information-checker-with-amazon-rekognition-lex-polly-dynamodb-s3-and-lambda-35fd215b51b0).
I have made a few additions which you can the find the details in [the Lex bot export file](lex/informationCheckerBot.zip)

### Adding Students

Students can be added by saying 'add student'.
When adding a user, a student name and required information is requested.
The student is saved after the picture of the student is taken.

### Checking Students

admins can check whether a student has a corresponding information by saying 'check student'
After the picture of the student is taken, the required information information is checked and shown if there is any.

### Logging out

admins can log out by saying 'log out'

## Uninstallation

**Please remember to delete the created AWS resources if they are not used anymore.**
A Bash script [uninstall.sh](uninstall.sh) is provided to delete the created resources.
Please **be carefull when running this script as it will delete the resources that are configured in config.json file.**

## Thanks

I would like to thank [Danilo Poccia](https://danilop.net/) as I used his init.sh script from his [LambdAuth](https://github.com/danilop/LambdAuth) project to create install.sh.
