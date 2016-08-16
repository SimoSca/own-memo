### prerequisites and definitions

require "yaml"

### Di una tree prendo solo le directory e le ritorno in formato Hash
### con un poco di sforzo posso usare qualche convenzione per fare in modo
### che le directory vengano contate oppure ignorate automaticamente
def treeDir startDir, maxDeep = 1 , actualDeep = 0
    actualDeep = actualDeep + 1
    path = {}
    Dir["#{startDir}/*"].each do |e|
        if File.directory?(e)
            key = File.basename(e)
            val = if actualDeep >= maxDeep  then nil
                else treeDir(e, maxDeep, actualDeep)
            end
            path[key] = val
        end
    end
    return path
end



########### TASKS ###########

task :'my-jekyll' => [:codesTree] do
    # ruby "test/unittest.rb"
    #puts "now..."
end

task :codesTree do
    puts "codesTree"

    # get base config for codes
    $codeBasePath = "_data/.codes.yml"
    $codeBase = if YAML.load(File.read($codeBasePath)) then
            YAML.load(File.read($codeBasePath))
        else
            {}
    end
    # merge with computed tree
    $codeDir = "_codes/"
    $codeBase['tree'] = treeDir($codeDir, 2)

    # compare with old config file
    $codePath = "_data/codes.yml"
    $code = if YAML.load(File.read($codePath)) then
            YAML.load(File.read($codePath))
        else
            {}
    end

    # if differs then update the $codePath with $codeBase
    if $code != $codeBase then
        File.open("#{$codePath}", "w") do |file|
            file.write $codeBase.to_yaml
            puts "updated #{$codePath}"
        end
    end

end
