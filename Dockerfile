FROM bitnami/laravel:10
WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist
RUN npm install && npm run build
EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
