Folder Project Type
================


This is a simple plugin powered by `Simone Scardoni`.

The goal is create a project from simple local folder so that it can be part or a group.

That's it: no other fashion skils, no build tool ... in opened project you'll have the basic `Netbeans` workaround, and nothing more!

If you need specific tools, then `Netbeans` offers well improved `project type`, and is no my purpose amellorate them.


TODO
=====

refactor renaming all in `MixProject`

USAGE
-------------

Simpli add a file named `netbeans-folder.txt` or `.netbeans-folder` to your project root.

If you wanna add this folder as project, then in `Netbeans`: 

````
File > Open Project 
````

and you naviate to your root project: it will be automatically added to `Netbeans Projects` :

the `Projects` view related to added project will simply display the folder tree, as in `Files` view.


### NB:

This plugin is only to `Open Project` 

````
not add new project type, but only the ability of open a simple folder as project!
````

### Personal Note

at my own risk, I can install the module into the Development IDE (not the runned instance to test the module):

1. right click on Module Project
2. `install/reload in Development IDE` 
3. it appears an allert: if sure then accept


DISCLAIMER
==========

I'm not a `Netbeans` developer: this module was born to my specific needs.

````
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, 
THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY 
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES 
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
````

Furthermore

* The software could include technical or other mistakes, inaccuracies or typographical errors.
* At any time without prior notice, we may make changes to the links pointing to third-party software or documentation made available on the third-party's website.
* The software may be out of date, and we make no commitment to update such materials.
* We assume no responsibility for errors or omissions in the third-party software or documentation available from its website.
* In no event shall we be liable to you or any third parties for any special, punitive, incidental, indirect or consequential damages of any kind, or any damages whatsoever, including, without limitation, those resulting from loss of use, lost data or profits, or any liability, arising out of or in connection with the use of this third-party software.


References
------------------

- [https://platform.netbeans.org/tutorials/nbm-projecttype.html](https://platform.netbeans.org/tutorials/nbm-projecttype.html)

- [https://platform.netbeans.org/tutorials/71/nbm-projecttype.html](https://platform.netbeans.org/tutorials/71/nbm-projecttype.html)
