echo "*** DEPLOYING ***"

rsync -rvu --exclude '.git' --exclude 'bin' --exclude 'tests' --exclude '/*-config.json' --exclude '/.*' --exclude '/*.sh' --exclude '/*.xml' --exclude '/*.dist' --exclude '/*.md' ubuntu@18.139.217.66:/var/www/html/MyProject/

echo "*** DEPLOYED ***"