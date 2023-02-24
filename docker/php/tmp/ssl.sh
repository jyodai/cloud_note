cd /etc/apache2
mkdir ssl

cd /tmp
mkdir work
cd /work
mkdir create_ssl
cd create_ssl

touch subjectnames.txt
echo 'subjectAltName = DNS:your-domain.com, IP:127.0.0.1' > subjectnames.txt

openssl genrsa 2048 > server.key
openssl req -new -key server.key -subj "/C=JP/ST=Some-State/O=Some-Org/CN=127.0.0.1" > server.csr
openssl x509 -days 3650 -req -extfile subjectnames.txt -signkey server.key < server.csr > server.crt

cp server.crt /etc/apache2/ssl/server.crt
cp server.key /etc/apache2/ssl/server.key