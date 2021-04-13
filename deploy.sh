cd /var/www/html/MyProject && git pull origin dev
rsync -ravz -e 'ssh -p 22' --chown=ec2-user:ec2-user --delete --exclude=".git*" --exclude=".env" --exclude="*.sh" --exclude="README.md" /main/secretapi/ ubuntu@18.139.217.66:/var/www/html/MyProject/

echo "*** DEPLOYED ***"