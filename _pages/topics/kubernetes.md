---
layout:     default
title:      Kubernetes
permalink:  /topics/k8s/
---


SINGLE NODE CLUSTER
-------------------

#### Installazione Minikube (x aws)

- [https://www.radishlogic.com/kubernetes/running-minikube-in-aws-ec2-ubuntu/](https://www.radishlogic.com/kubernetes/running-minikube-in-aws-ec2-ubuntu/),
    esempio completo con configurazioni per aws.
    

AWS CLUSTER
-----------

Creati con `kops`:

- [create-a-high-availability-kubernetes-cluster-on-aws-with-kops/](https://www.poeticoding.com/create-a-high-availability-kubernetes-cluster-on-aws-with-kops/),
    da leggere in quanto sfrutta delle versioni molto leggere delle istanze: la scelta e' importante! 
    Ci sono anche tante belle spiegazioni, quindi stra consigliato!

- [2018-05-20-setting-up-a-kubernetes-cluster-on-aws-in-5-minutes](https://ramhiser.com/post/2018-05-20-setting-up-a-kubernetes-cluster-on-aws-in-5-minutes/),
    tutorial ben fatto con utilizzo di `KOPS`.
    
- [tutorial-deploying-kubernetes-to-aws-using-kops](https://codefresh.io/kubernetes-tutorial/tutorial-deploying-kubernetes-to-aws-using-kops/),
    simile a quello sopra
    
- [https://kubernetes.io/docs/setup/custom-cloud/kops/](https://kubernetes.io/docs/setup/custom-cloud/kops/),
    tutorial ufficiale di `k8s`
    
- [kubernetes-clusters-aws-kops](https://aws.amazon.com/blogs/compute/kubernetes-clusters-aws-kops/),
    guida ufficiale di `aws`
    
    
Creazione manuale:

- [setup-kubernetes-cluster-on-aws-ec2](http://www.tothenew.com/blog/setup-kubernetes-cluster-on-aws-ec2/)


Overview generale su `k8s` e `aws`:

- [migrating-to-kubernetes-with-zero-downtime-the-why-and-how-d64ba9a92619](https://www.manifold.co/blog/migrating-to-kubernetes-with-zero-downtime-the-why-and-how-d64ba9a92619),
    con dei bei grafici
    
    
PERSISTENCE VOLUMES
-------------------

- [kubernetes-storage-performance-comparison](https://medium.com/vescloud/kubernetes-storage-performance-comparison-9e993cb27271)