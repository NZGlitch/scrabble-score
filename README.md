ssh into server

cd your-repo
git pull origin main
docker-compose down
docker-compose up --build -d


nginx config:

server {
    listen 80;
    server_name scrabble.threezor.com;

    # Redirect all HTTP to HTTPS
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl;
    server_name scrabble.threezor.com;

    # SSL certs from Let's Encrypt
    ssl_certificate /etc/letsencrypt/live/scrabble.threezor.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/scrabble.threezor.com/privkey.pem;

    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}


nginx command:
sudo ln -s /etc/nginx/sites-available/scrabble /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
