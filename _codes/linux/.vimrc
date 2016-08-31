set autoindent
set number
set mouse=a

set tabstop=4       " The width of a TAB is set to 4.
                    " Still it is a \t. It is just that
                    " Vim will interpret it to be having
                    " a width of 4.

set shiftwidth=4    " Indents will have a width of 4

set softtabstop=4   " Sets the number of columns for a TAB

set expandtab       " Expand TABs to spaces

"/usr/share/vim/vim73/colors , per accedere a tutti i temi

	colorscheme desert

"for cuda highliting
au BufNewFile,BufRead *.cu set ft=cu
au BufNewFile,BufRead *.sed set ft=cpp
