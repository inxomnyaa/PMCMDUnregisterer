# PMCMDUnregisterer

Unregisters commands by name in the config

## Usage
Put the commands to unregister into the config.yml, separated by comma

Example:


```
---
[help,version,plugins]
...
```

## Disclaimer

This will also remove commands for operators and the console!

(Due to this i disabled removing the "stop"-command)