server {
    listen {listen};
    server_name {host};

    location /api/ {
        root {root};
        fastcgi_pass {fpm};
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root/index.php;
        include fastcgi_params;
    }

    location / {
        proxy_pass {nuxt};
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
