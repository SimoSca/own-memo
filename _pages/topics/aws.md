AWS AMAZON
==========


IAM
---

Esempio di creazione utenze `Admin` con **MFA** device.

Questo link [https://blog.jayway.com/2017/11/22/aws-cli-mfa/](https://blog.jayway.com/2017/11/22/aws-cli-mfa/)
porta a una risorsa che spiega bene come fare (ho provato e Funzia!).
Oltre alla logica utilizzata, vi e' poi questo link [https://github.com/matsev/aws-cli-mfa](https://github.com/matsev/aws-cli-mfa),
che porta al repo git col file di esempio da caricare in `CloudFormation` (vedi in questo repository la sezione `_codes`).

Alcune letture utili:

- [https://github.com/broamski/aws-mfa](https://github.com/broamski/aws-mfa),
    tool per la cli (script `python`)

- [aws-resource-iam-role.html](https://docs.aws.amazon.com/en_us/AWSCloudFormation/latest/UserGuide/aws-resource-iam-role.html), 
    sito ufficiale di `aws` in cui spiega sinteticamente l'uso dei ruoli
    
- [enforce-mfa-other-account-access-bucket/](https://aws.amazon.com/it/premiumsupport/knowledge-center/enforce-mfa-other-account-access-bucket/),
    altra modalita' che se pur in maniera differente, in ogni caso suggerisce alcuni template di `CloudFormation`
    
- [authenticate-mfa-cli](https://aws.amazon.com/it/premiumsupport/knowledge-center/authenticate-mfa-cli/),
    altra modalita' per raggiungere lo scopo di cui sopra, ma ad `hoc` per utente. 
    Contiene pero' delle info relative a come utilizzare il `token` e impostare un duration in seconds
    
- [id_credentials_mfa_enable_cliapi.html](https://docs.aws.amazon.com/en_us/IAM/latest/UserGuide/id_credentials_mfa_enable_cliapi.html),
    istruzioni via cli
    
