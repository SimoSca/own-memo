---
title:      Alexa
permalink:  /project/alexa
---

#### Alexa Skill - Aws Lambda

Se decido di utilizzare una `Lambda Function` per gestire le SKILL allora mi conviene sempre avere presente (come per una quqlsiasi API)
il formato delle `request` e del `response`.

Vedere quanto segue:

- [https://medium.com/@rania.zyane/amazon-alexa-build-a-new-skill-with-aws-lambda-to-automatically-build-a-circleci-bot-project-fb65835b7a47](https://medium.com/@rania.zyane/amazon-alexa-build-a-new-skill-with-aws-lambda-to-automatically-build-a-circleci-bot-project-fb65835b7a47)

- [https://medium.com/iotforall/build-your-first-custom-alexa-skill-in-10-minutes-2d27485727ed](https://medium.com/iotforall/build-your-first-custom-alexa-skill-in-10-minutes-2d27485727ed)

#### Aws Lambda Logs

Per loggare posso usare `Cloud Watch`, vedi:

- [https://docs.aws.amazon.com/en_us/lambda/latest/dg/nodejs-prog-model-logging.html](https://docs.aws.amazon.com/en_us/lambda/latest/dg/nodejs-prog-model-logging.html)


### Links

- [https://www.clodo.it/blog/primo-impatto-con-alexa-amazon-echo/](https://www.clodo.it/blog/primo-impatto-con-alexa-amazon-echo/)



#### Esempio di Evento che giunge a Lambda in NodeJs

Node Handler:
````typescript
exports.handler = (event, context, callback) => {
     console.log(event);
     console.log(context);
     console.log(callback)
}
````

it' event:

````
{  
   version:'1.0',
   session:{  
      new:true,
      sessionId:'amzn1.echo-api.session.<ses id>',
      application:{  
         applicationId:'amzn1.ask.skill.<id>'
      },
      user:{  
         userId:'amzn1.ask.account.<id>'
      }
   },
   context:{  
      System:{  
         application:[  
            Object
         ],
         user:[  
            Object
         ],
         device:[  
            Object
         ],
         apiEndpoint:'https://api.eu.amazonalexa.com',
         apiAccessToken:'ey...'
      },
      Viewport:{  
         experiences:[  
            Array
         ],
         shape:'RECTANGLE',
         pixelWidth:1024,
         pixelHeight:600,
         dpi:160,
         currentPixelWidth:1024,
         currentPixelHeight:600,
         touch:[  
            Array
         ]
      }
   },
   request:{  
      type:'IntentRequest',
      requestId:'amzn1.echo-api.request.<req id>',
      timestamp:'2018-12-09T14:20:10Z',
      locale:'it-IT',
      intent:{  
         name:'ripetere',
         confirmationStatus:'NONE',
         slots:[  
            Object
         ]
      },
      dialogState:'STARTED'
   }
}
````

it's context:

````
{  
   callbackWaitsForEmptyEventLoop:[  
      Getter/Setter
   ],
   done:[  
      Function:done
   ],
   succeed:[  
      Function:succeed
   ],
   fail:[  
      Function:fail
   ],
   logGroupName:'/aws/lambda/<my func>',
   logStreamName:'2018/12/09/[$LATEST]342eede57e9b4c65b67038e480488392',
   functionName:'<my func>',
   memoryLimitInMB:'512',
   functionVersion:'$LATEST',
   getRemainingTimeInMillis:[  
      Function:getRemainingTimeInMillis
   ],
   invokeid:'<id>',
   awsRequestId:'<id>',
   invokedFunctionArn:'arn:aws:lambda:eu-west-1:<my id>:function:<my func>'
}
````

La callback invece e' una function senza dettagli.