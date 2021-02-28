# todo-list
Test project

### Software needed

You have Linux + docker + docker-compose installed.

Make sure your used belonges to `docker` group by running `groups` in `bash`

If `docker` group is not listed, then run:

```sudo usermod -aG docker $(whoami)```

#### Make sure you project files have correct owner

If you have previously run docker containers as root (via sudo), some files can have root user owner.

So in the root of the project:

```sudo chown -R $(whoami):$(id -g -n $(whoami)) *```

### Initial project installation

Make sure you have docker installed and docker daemon running.

E.g. `sudo service docker start`.

Make sure all other docker containers are stopped.
```bash
docker stop $(docker ps -aq)
```

Please also stop DB and Web servers probably running at your computer to be sure
`docker` doesn't try to use ports used by other services.

```bash
git clone https://github.com/minibo29/todo-list.git

cd todo-list

git submodule init && git submodule update

# Start project docker containers.
# This will take long time for the first run. Wait...
docker/up

# When docker containers are built and run login to the workspace container
# and install npm extensions.
docker/login
yarn install
yarn encore dev
exit
```
Now you should be able to open the localhost web-site.

To stop the project run `docker/stop`
