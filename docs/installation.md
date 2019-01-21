# Installation

## Using a existing Ilias installation

**TODO**

## Using a combined Docker image for a "Demo" Installation

This project features a Docker image containing a "Demo" version of Ilias with
the plugin preloaded. The Docker image is built with every push. It is based on
[whiledo/ilias-transientmysql](https://hub.docker.com/r/whiledo/ilias-transientmysql/) and
can be pulled with this command:

```
docker pull docker.gitlab.tab-e.de/qpi-sql/qpi-sql:[Tag]
```

The `[Tag]` has to be replaced by the branch the image should be based on. By
default `master` is recommended.

A minimalistic `docker run` command is:

```
docker run -d \
  -p [Port]:80 \
  --restart always \
  --name qpi-sql-ilias \
  docker.gitlab.tab-e.de/qpi-sql/qpi-sql:[Tag]
```

The `[Port]` has to be an available port at the Docker host and equals the
port ilias can be reached after being started. The environment variables described at
[whiledo/ilias-transientmysql](https://hub.docker.com/r/whiledo/ilias-transientmysql/)
are valid for this image, too. Please mind that Ilias might take some time to boot
before it can be reached.

The default login credentials are:

|Username|Password|
|:-:|:-:|
|root|homer123|

After start the plugin has to be activate like described above.
