CREATE DATABASE  IF NOT EXISTS `camisaria_xpto` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `camisaria_xpto`;
-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: camisaria_xpto
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nome_categoria` varchar(100) NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Camisas'),(2,'Calças'),(3,'Acessórios');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produto`
--

DROP TABLE IF EXISTS `produto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produto` (
  `id_produto` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_de_atualizacao` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `descricao` text,
  `preco` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_produto`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto`
--

LOCK TABLES `produto` WRITE;
/*!40000 ALTER TABLE `produto` DISABLE KEYS */;
INSERT INTO `produto` VALUES (6,'Camisa Social Office','2024-10-18 15:32:35',NULL,'Camisa Social Manga Longa Masculina Office Chumbo',144.50),(9,'Camisa de Grife \"Luxo\"','2024-10-18 15:39:46',NULL,'Camisa de seda, com design exclusivo e acabamento de alta qualidade.',249.90),(7,'Calça Tactel Roma','2024-10-18 15:33:42',NULL,'Calça Tactel Jogger Azul Marinho - Roma',79.90),(8,'Carteira Couro ','2024-10-18 15:35:36',NULL,'Carteira Couro Porta Cartões e Documentos - Preta',59.90),(10,'Camisa \"Rockstar\"','2024-10-18 15:40:22',NULL,'Camisa de algodão com estampa de banda e detalhes rasgados.',89.90),(11,'Camisa \"Vintage\"','2024-10-18 15:40:38','2024-10-18 15:44:30','Camisa de algodão com estampa retrô e mangas curtas.',79.90),(12,'Camisa de Treino \"Performance\"','2024-10-18 15:41:01',NULL,'Camisa de poliamida com tecnologia de absorção de umidade.',79.90),(13,'Camisa de Algodão \"Toque Suave\"','2024-10-18 15:41:39',NULL,'Camisa de algodão super macia, disponível em várias cores.',69.90),(14,'Camisa \"Estilo Urbano\"','2024-10-18 15:42:50',NULL,'Camisa de algodão com estampa gráfica, perfeita para um look descolado',89.90),(15,'Calça Jeans \"Clássica Slim\"','2024-10-18 15:43:26','2024-10-18 15:44:51','Calça jeans de corte slim, feita de denim confortável com lavagem escura.',139.90),(16,'Calça Social \"Elegância\"','2024-10-18 15:43:54','2024-10-18 15:45:17','Calça social de poliéster, com corte slim e detalhe de pregas. Perfeita para o ambiente de trabalho.',149.90);
/*!40000 ALTER TABLE `produto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produto_categoria`
--

DROP TABLE IF EXISTS `produto_categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produto_categoria` (
  `id_produto` int NOT NULL,
  `id_categoria` int NOT NULL,
  PRIMARY KEY (`id_produto`,`id_categoria`),
  KEY `id_categoria` (`id_categoria`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto_categoria`
--

LOCK TABLES `produto_categoria` WRITE;
/*!40000 ALTER TABLE `produto_categoria` DISABLE KEYS */;
INSERT INTO `produto_categoria` VALUES (6,1),(7,2),(8,3),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(15,2),(16,2);
/*!40000 ALTER TABLE `produto_categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produto_tamanho`
--

DROP TABLE IF EXISTS `produto_tamanho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produto_tamanho` (
  `id_produto` int NOT NULL,
  `id_tamanho` int NOT NULL,
  `quantidade` int DEFAULT '0',
  PRIMARY KEY (`id_produto`,`id_tamanho`),
  KEY `id_tamanho` (`id_tamanho`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto_tamanho`
--

LOCK TABLES `produto_tamanho` WRITE;
/*!40000 ALTER TABLE `produto_tamanho` DISABLE KEYS */;
INSERT INTO `produto_tamanho` VALUES (6,1,20),(7,1,50),(9,1,20),(9,4,20),(10,1,50),(9,3,0),(6,2,90),(7,2,50),(7,3,50),(7,4,50),(8,1,60),(10,2,50),(11,1,15),(12,1,68),(12,2,50),(13,1,85),(13,2,26),(14,1,46),(14,2,154),(14,3,200),(15,1,65),(15,2,70),(16,2,6),(16,1,0),(11,2,26),(16,3,6);
/*!40000 ALTER TABLE `produto_tamanho` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tamanho`
--

DROP TABLE IF EXISTS `tamanho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tamanho` (
  `id_tamanho` int NOT NULL AUTO_INCREMENT,
  `descricao_tamanho` varchar(50) NOT NULL,
  PRIMARY KEY (`id_tamanho`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tamanho`
--

LOCK TABLES `tamanho` WRITE;
/*!40000 ALTER TABLE `tamanho` DISABLE KEYS */;
INSERT INTO `tamanho` VALUES (1,'P'),(2,'M'),(3,'G'),(4,'GG');
/*!40000 ALTER TABLE `tamanho` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-18 16:16:08
