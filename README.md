Zf2Generator
============

A quick and dirty toolset to create zf2 code.

I am sceptical about code generation, however when writing my own templates and tools it is a good starting point at time.

In particular I noticed that very often I would put service locator factories into the Module.php as closures (which cannot be cached) rather than in their own factories which is better practice. I always mean to refactor asap but quite often don't get around to it for some time.

So in order to trick myself over my own laziness, I wrote this simple console application which is similar to ZFTool [https://github.com/zendframework/ZFTool] in some respect but seems to go a bit further in this particular matter.

I will probably try and see if I can integrate it into ZFTool in the near future.


