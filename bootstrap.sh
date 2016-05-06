function install {
    echo installing $1
    # shift
    sudo yum -y install "$@" >/dev/null 2>&1
}

echo 'Install base dev tools'
install gcc
install gcc-c++
install make
install wget
install git
install zsh
install openssl-devel
install vim-enhanced
install ncurses-devel

echo 'Install emacs 24.5'
wget http://public.p-knowledge.co.jp/gnu-mirror/emacs/emacs-24.5.tar.gz >/dev/null 2>&1
tar xvfz emacs-24.5.tar.gz >/dev/null 2>&1
cd emacs-24.5
./configure --without-toolkit-scroll-bars --without-xaw3d --without-sound --without-pop --without-xpm --without-tiff --without-rsvg --without-gconf --without-gsettings --without-selinux --without-gpm --without-makeinfo --with-x --with-gnutls >/dev/null 2>&1
make >/dev/null 2>&1
sudo make install >/dev/null 2>&1

#echo 'Clone my .emacs.d'
#git clone https://github.com/shotana/emacs.d ~/.emacs.d >/dev/null 2>&1

#echo 'Change shell zsh'
#expect -c '
#spawn chsh -s /bin/zsh >/dev/null 2>&1
#expect \"パスワード:\"
#send -- \"vagrant\n\"
#'

#echo 'Apply oh-my-zsh'
#sh -c "$(wget https://raw.github.com/robbyrussell/oh-my-zsh/master/tools/install.sh -O -)" >/dev/null 2>&1
#sudo sed -i -e 's/ZSH_THEME="robbyrussell"/ZSH_THEME="steeef"/g' ~/.zshrc >/dev/null 2>&1

echo 'Install PHP5.6'
sudo rpm -ivh https://dl.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm >/dev/null 2>&1
sudo sed -i -e "s/enabled *= *1/enabled=0/g" /etc/yum.repos.d/epel.repo >/dev/null 2>&1
sudo rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm >/dev/null 2>&1
sudo sed -i -e "s/enabled *= *1/enabled=0/g" /etc/yum.repos.d/remi.repo >/dev/null 2>&1
install php --enablerepo=epel --enablerepo=remi --enablerepo=remi-php56
install php-xml --enablerepo=epel --enablerepo=remi --enablerepo=remi-php56
install php-mbstring --enablerepo=epel --enablerepo=remi --enablerepo=remi-php56
install php-mcrypt --enablerepo=epel --enablerepo=remi --enablerepo=remi-php56
install php-mysql --enablerepo=epel --enablerepo=remi --enablerepo=remi-php56
install php-opcache --enablerepo=epel --enablerepo=remi --enablerepo=remi-php56
install php-fpm --enablerepo=epel --enablerepo=remi --enablerepo=remi-php56
echo 'Start php-fpm'
sudo service php-fpm start >/dev/null 2>&1

echo 'Configure php-fpm corresponding to nginx'
sudo cp /etc/php-fpm.d/www.conf /etc/php-fpm.d/www.conf.bak
sudo sed -i -e "s/user = apache/user = nginx/g" /etc/php-fpm.d/www.conf >/dev/null 2>&1

echo 'Install composer'
curl -sS https://getcomposer.org/installer | php >/dev/null 2>&1
sudo mv composer.phar /usr/bin/composer >/dev/null 2>&1

echo 'Install nginx'
sudo rpm -ivh http://nginx.org/packages/centos/6/noarch/RPMS/nginx-release-centos-6-0.el6.ngx.noarch.rpm >/dev/null 2>&1
install nginx
echo 'Start nginx'
sudo service nginx start >/dev/null 2>&1

echo 'Install MySQL5.6'
install http://dev.mysql.com/get/mysql-community-release-el6-5.noarch.rpm
install mysql
install mysql-devel
install mysql-server
install mysql-utilities

echo 'Start MySQL'
sudo service mysqld start >/dev/null 2>&1
echo 'Set password for root user'
mysql -uroot <<SQL
set password for 'root'@'localhost' = password('root');
create database tanakalab_v2;
SQL

echo 'Set chkconfig'
sudo chkconfig mysqld on
sudo chkconfig nginx on
sudo chkconfig php-fpm on

echo 'Disabled iptables'
sudo service iptables stop >/dev/null 2>&1

echo 'Disabled SElinux'
sudo setenforce 0

echo 'Install nvm and nodejs 0.12.9'
git clone git://github.com/creationix/nvm.git ~/.nvm >/dev/null 2>&1
source ~/.nvm/nvm.sh
nvm install 0.12.9 >/dev/null 2>&1
nvm alias default v0.12.9 >/dev/null 2>&1
echo 'if [[ -s ~/.nvm/nvm.sh ]];' >> ~/.bash_profile
echo '  then source ~/.nvm/nvm.sh' >> ~/.bash_profile
echo 'fi' >> ~/.bash_profile

echo 'Install gulp'
npm install gulp -g >/dev/null 2>&1
echo 'Install bower'
npm install bower -g >/dev/null 2>&1

echo 'Setup Tanakalab'
cd /vagrant
cp .env.example .env

echo 'run composer install'
composer install >/dev/null 2>&1

echo 'Regenerate autoload files'
php artisan clear-compiled >/dev/null 2>&1
php artisan optimize >/dev/null 2>&1
composer dump-autoload >/dev/null 2>&1

echo 'Migrate database'
php artisan migrate >/dev/null 2>&1

echo 'Create test user'
php artisan db:seed --class=TestUserSeeder

echo 'Run npm install'
npm install >/dev/null 2>&1

echo 'Run bower install'
bower install >/dev/null 2>&1

echo 'Run gulp'
gulp >/dev/null 2>&1

echo -e "\n\n"
echo "Done."

echo -e "\n\n"
echo "Run server by this cmd: $ php artisan serve --host 0.0.0.0"
