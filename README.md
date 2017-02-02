# Gelatinpixel.com

__These notes are intended for the master branch only!__

```master``` branch houses all assets, including the old photoblog engine (pixelpost) and is committed to BitBucket as ```origin```.

```gh-pages``` branch houses public only assets and is committed to GitHub as ```gh``` remote.

## GIT setup

```
### remotes
$ git remote -v

gh      git@github.com:datafunk/gelatinpixel.git (fetch)
gh      git@github.com:datafunk/gelatinpixel.git (push)
origin  ssh://git@bitbucket.org/datafunk/gelatinpixel.git (fetch)
origin  ssh://git@bitbucket.org/datafunk/gelatinpixel.git (push)

### branches
$ git branch

gh-pages
master
```

## DNS setup

_Enetica.com.au_ is the registrar for _gelatinpixel.com_ and is pointed at DigitalOcean  where gelatinpixel.com has A records to GitHub, its subdomains have C records to gelatinpixel.com


Enetica > DO2 (```163.47.8.114```) > GH (```192.30.252.153``` & ```192.30.252.154```)
