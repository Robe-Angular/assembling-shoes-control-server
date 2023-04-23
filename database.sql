CREATE DATABASE IF NOT EXISTS assembling_shoes_control;
USE assembling_shoes_control;



CREATE TABLE workers(
id              int(255) auto_increment not null,
creator         bigint(20) unsigned not null,
name            varchar(100) not null,
created_at      datetime DEFAULT NULL,
updated_at      datetime DEFAULT NULL,
CONSTRAINT fk_workers_user FOREIGN KEY(creator) REFERENCES users(id) ON DELETE CASCADE,
CONSTRAINT pk_workers PRIMARY KEY(id)

)ENGINE=InnoDb;

CREATE TABLE models_boot(
id              int(255) auto_increment not null,
creator         bigint(20) unsigned not null,
title           varchar(255) not null,
features        varchar(255) not null,
created_at      datetime DEFAULT NULL,
updated_at      datetime DEFAULT NULL,
CONSTRAINT  fk_models_boot_user FOREIGN KEY(creator) REFERENCES users(id) ON DELETE CASCADE,
CONSTRAINT pk_models_boot PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE orders(
id              int(255) auto_increment not null,
creator     	bigint(20) unsigned not null,
model_boot_worker_id         int(255)not null,
order_date      datetime DEFAULT CURRENT_TIMESTAMP,
created_at      datetime DEFAULT NULL,
updated_at      datetime DEFAULT NULL,
CONSTRAINT pk_orders PRIMARY KEY(id),
CONSTRAINT fk_orders_user FOREIGN KEY(creator) REFERENCES users(id) ON DELETE CASCADE,
CONSTRAINT fk_orders_model_boot_worker FOREIGN KEY(model_boot_worker_id) REFERENCES model_boot_worker(id) ON DELETE CASCADE
)ENGINE=InnoDb;



CREATE TABLE sizes(
id              int(255) auto_increment not null,
number          int(255)not null,
model_boot_id         int(255)not null,
created_at      datetime DEFAULT NULL,
updated_at      datetime DEFAULT NULL,
CONSTRAINT pk_sizes PRIMARY KEY(id),
CONSTRAINT fk_sizes_model_boot FOREIGN KEY(model_boot_id) REFERENCES models_boot(id) ON DELETE CASCADE
)ENGINE=InnoDb;


CREATE TABLE size_order(
id              int(255) auto_increment not null,
size_id     	int(255)not null,
order_id         int(255)not null,
quantity         int(255)not null,
created_at      datetime DEFAULT NULL,
updated_at      datetime DEFAULT NULL,
CONSTRAINT pk_size_order PRIMARY KEY(id),
CONSTRAINT fk_size_order_sizes FOREIGN KEY(size_id) REFERENCES sizes(id) ON DELETE CASCADE,
CONSTRAINT fk_size_order_orders FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE
)ENGINE=InnoDb;


CREATE TABLE model_boot_worker(
id              int(255) auto_increment not null,
model_boot_id         int(255)not null,
worker_id         int(255)not null,
created_at      datetime DEFAULT NULL,
updated_at      datetime DEFAULT NULL,
CONSTRAINT pk_model_boot_worker PRIMARY KEY(id),
CONSTRAINT fk_model_boot_worker_model_boot FOREIGN KEY(model_boot_id) REFERENCES models_boot(id) ON DELETE CASCADE,
CONSTRAINT fk_model_boot_worker_worker FOREIGN KEY(worker_id) REFERENCES workers(id) ON DELETE CASCADE
)ENGINE=InnoDb;




CREATE TABLE size_worker(
id              int(255) auto_increment not null,
worker_id     	int(255)not null,
size_id     	int(255)not null,
quantity 	int(255) not null,
created_at      datetime DEFAULT NULL,
updated_at      datetime DEFAULT NULL,
CONSTRAINT pk_size_worker PRIMARY KEY(id),
CONSTRAINT fk_size_worker_worker FOREIGN KEY(worker_id) REFERENCES workers(id) ON DELETE CASCADE,
CONSTRAINT fk_size_worker_size FOREIGN KEY(size_id) REFERENCES sizes(id) ON DELETE CASCADE
)ENGINE=InnoDb;