FROM mysql:8.0

# Set environment variables
ENV MYSQL_ROOT_PASSWORD=root_password
ENV MYSQL_DATABASE=challenge_qa
ENV MYSQL_USER=qa_user
ENV MYSQL_PASSWORD=qa_password

# Copy custom MySQL configuration if needed
COPY docker/mysql/my.cnf /etc/mysql/conf.d/

# Copy initialization scripts
COPY docker/mysql/init/ /docker-entrypoint-initdb.d/

# Expose MySQL port
EXPOSE 3306