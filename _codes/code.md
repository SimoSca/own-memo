---
layout: default
title: Code
permalink: /code/
---

Raccolta di esempi di codice memorizzata al fine di avere dei riferimenti sempre pronti all'uso.

> Ogni directory per essere tracciata dal loop seguente deve contenere un file `jekyll.md` contenente solo l'intestazione vuota.
> Naturalmente posso anche aggiungerci del testo.

### Files:
<ul>
    {% for node in site.codes %}
        {% unless node.relative_path contains 'code.md' %}

            <li><a href="{{site.repoUrl}}/{{ node.relative_path | replace_regex: '\/[^\/]+\.md' , '' }}">
                {{ node.relative_path | replace_regex: '\/[^\/]+\.md' , '' }}
            </a></li>

        {% endunless %}
    {% endfor %}
</ul>
