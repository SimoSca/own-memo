---
title:      ALFRED
permalink:  /tools/alfred/
---

**Mac app: Alfred**

e' un grandioso tool per automatizzare varie azioni in maniera semplice e intuitiva.

In ogni caso ho raccolto alcuni link che possono dare suggerimenti per iniziare:


- [http://lifehacker.com/5993430/how-to-put-together-and-share-your-alfred-workflows-and-show-us-yours](http://lifehacker.com/5993430/how-to-put-together-and-share-your-alfred-workflows-and-show-us-yours)

- [https://www.alfredapp.com/help/features/file-search/](https://www.alfredapp.com/help/features/file-search/)

Tutorial base:

- [https://computers.tutsplus.com/tutorials/alfred-workflows-for-beginners--mac-55446](https://computers.tutsplus.com/tutorials/alfred-workflows-for-beginners--mac-55446)

E qui uno molto importante per capire come creare la lista di voci suggerite da alfred:

- [https://computers.tutsplus.com/tutorials/alfred-workflows-for-advanced-users--mac-60963](https://computers.tutsplus.com/tutorials/alfred-workflows-for-advanced-users--mac-60963)



### Output 

Output formats:

- [https://www.alfredapp.com/help/workflows/inputs/script-filter/json/](https://www.alfredapp.com/help/workflows/inputs/script-filter/json/)



### Publish My Alfred Workflow

From [http://alfredworkflow.readthedocs.io/en/latest/tutorial_2.html](http://alfredworkflow.readthedocs.io/en/latest/tutorial_2.html)

#### The Packal Updater

The simplest way in terms of implementation is to upload your workflow to Packal.org. If you release a new version, any user who also uses the Packal Updater workflow will then be notified of the updated version. The disadvantage of this method is it only works if a user installs and uses the Packal Updater workflow.

####GitHub releases

A slightly more complex to implement method is to use Alfred-Workflow’s built-in support for updates via GitHub releases. If you tell your Workflow object the name of your GitHub repo and the installed workflow’s version number, Alfred-Workflow will automatically check for a new version every day.

By default, Alfred-Workflow won’t inform the user of the new version or update the workflow unless the user explicitly uses the workflow:update “magic” argument, but you can check the Workflow.update_available attribute and inform the user of the availability of an update if it’s True.


See Self-updating in the User Manual for information on how to enable your workflow to update itself from GitHub.

