# For create docker image for your project use below command

-- sudo docker image build {path} //of DockerFile

# Then Build the docker images use below commmand

-- sudo docker-compose build

# Build the docker images with no cache 

-- sudo docker-compose build --no-cache

- for create build for specfic service use below command

-- for example -: sudo docker-compose build <service name> -- for example phpmyadmin(service name under docker-compose.yml)

# Then run the composer up command for creating container for services

-- sudo docker-compose up

# To check logs of specific service

-- sudo docker logs -f  <container name> 

# To restart the specific service

-- sudo docker restart <container name> 

# For import sql for mysql use below command 

-- sudo docker exec -i <container name> mysql -uadmin -padmin@123  database name < file path

# To stop the specific service

-- sudo docker stop <container name> 

# To remove the specific service

-- sudo docker rm <container name> 

# For check all container use below command

-- sudo docker ps

# To run migration in docker

-- sudo docker exec -i <conatiner id of web> php console.php module/migrate

# To check container memory usage

-- sudo docker stats






