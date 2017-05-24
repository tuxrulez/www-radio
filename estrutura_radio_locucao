-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (armv7l)
--
-- Host: localhost    Database: radio_locucao
-- ------------------------------------------------------
-- Server version	5.5.41-0+wheezy1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `locucao`
--

DROP TABLE IF EXISTS `locucao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locucao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(300) NOT NULL,
  `post` text,
  `sequencia` text NOT NULL,
  `tempo` int(11) NOT NULL DEFAULT '0',
  `repeticoes_hora` int(3) NOT NULL DEFAULT '1',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `dia_semana` set('1','2','3','4','5','6','7') NOT NULL DEFAULT '1,2,3,4,5,6,7',
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `hora_inicio` int(6) NOT NULL DEFAULT '0',
  `hora_fim` int(6) NOT NULL DEFAULT '86399',
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_edicao` timestamp NULL DEFAULT NULL,
  `deletado` tinyint(1) NOT NULL DEFAULT '0',
  `tipo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locucao`
--

LOCK TABLES `locucao` WRITE;
/*!40000 ALTER TABLE `locucao` DISABLE KEYS */;
INSERT INTO `locucao` VALUES (3,'dasd','{\"id\":\"0\",\"titulo\":\"dasd\",\"zz_locucao_abertura\":\"Extra - locucao ofertas fim de semana.mp3\",\"zz_locucao_categoria-0\":\"bazar\",\"zz_locucao_produto-0\":\"bazar\\/Extra - locucao bicicleta caloi 21 velocidades.mp3\",\"zz_locucao_parcelamento-0\":\"Extra - locucao 7 vezes de.mp3\",\"preco-real-0\":\"Extra - locucao 5 Reais.mp3\",\"preco-centavos-0\":\"\",\"repetir\":\"0\",\"zz_locucao_repeticao\":\"\",\"zz_locucao_fechamento\":\"Extra - locucao Mais Barato Mesmo.mp3\",\"dataInicio\":\"13\\/02\\/2015\",\"horaInicio\":\"00\",\"minutoInicio\":\"00\",\"dataFim\":\"13\\/02\\/2015\",\"horaFim\":\"23\",\"minutoFim\":\"00\",\"repeticoesHora\":\"1\",\"dias\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\"]}','{\"zz_locucao_abertura\":{\"nome\":\"ofertas fim de semana\",\"arquivo\":\"Extra - locucao ofertas fim de semana.mp3\",\"genero\":\"zz_locucao_abertura\",\"data_inicio\":\"2015-01-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":33.45},\"zz_locucao_produto-0\":{\"nome\":\"bicicleta caloi 21 velocidades\",\"arquivo\":\"Extra - locucao bicicleta caloi 21 velocidades.mp3\",\"genero\":\"zz_locucao_produto_bazar\",\"data_inicio\":\"2015-01-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":5.89},\"zz_locucao_parcelamento-0\":{\"nome\":\"7 vezes de\",\"arquivo\":\"Extra - locucao 7 vezes de.mp3\",\"genero\":\"zz_locucao_parcelamento\",\"data_inicio\":\"2015-01-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":1.9},\"preco-real-0\":{\"nome\":\"5 Reais\",\"arquivo\":\"Extra - locucao 5 Reais.mp3\",\"genero\":\"zz_locucao_preco\",\"data_inicio\":\"2015-01-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":1.3},\"preco-centavos-0\":null,\"zz_locucao_fechamento\":{\"nome\":\"Mais Barato Mesmo\",\"arquivo\":\"Extra - locucao Mais Barato Mesmo.mp3\",\"genero\":\"zz_locucao_fechamento\",\"data_inicio\":\"2014-11-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":3.34}}',48,1,1,'1,2,3,4,5,6,7','2015-02-13','2015-02-13',0,82800,'2015-02-13 16:36:53','2015-02-13 16:36:53',1,'oferta'),(4,'asda','{\"id\":\"4\",\"titulo\":\"asda\",\"zz_locucao_avisos\":\"Extra - locucao aviso pao quentinho.mp3\",\"dataInicio\":\"13\\/02\\/2015\",\"horaInicio\":\"00\",\"minutoInicio\":\"00\",\"dataFim\":\"13\\/02\\/2015\",\"horaFim\":\"23\",\"minutoFim\":\"00\",\"repeticoesHora\":\"6\",\"dias\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\"]}','{\"zz_locucao_avisos\":{\"nome\":\"aviso pao quentinho\",\"arquivo\":\"Extra - locucao aviso pao quentinho.mp3\",\"genero\":\"zz_locucao_avisos\",\"data_inicio\":\"2015-01-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":28.26}}',31,6,1,'1,2,3,4,5,6,7','2015-02-13','2015-02-13',0,82800,'2015-02-13 16:37:12','2015-02-13 16:46:30',1,'aviso');
/*!40000 ALTER TABLE `locucao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `tabela` varchar(100) NOT NULL,
  `operacao` varchar(1) NOT NULL COMMENT 'crud',
  `tabela_id` int(11) NOT NULL COMMENT 'id da linha na tabela',
  `query` text,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
INSERT INTO `log` VALUES (4,1,'locucao','c',3,'INSERT INTO locucao(titulo, post, sequencia, tempo, repeticoes_hora, ativo, dia_semana, data_inicio, data_fim, hora_inicio, hora_fim, data_criacao, data_edicao, tipo) VALUES(\"dasd\", \"{\"id\":\"0\",\"titulo\":\"dasd\",\"zz_locucao_abertura\":\"Extra - locucao ofertas fim de semana.mp3\",\"zz_locucao_categoria-0\":\"bazar\",\"zz_locucao_produto-0\":\"bazar\\/Extra - locucao bicicleta caloi 21 velocidades.mp3\",\"zz_locucao_parcelamento-0\":\"Extra - locucao 7 vezes de.mp3\",\"preco-real-0\":\"Extra - locucao 5 Reais.mp3\",\"preco-centavos-0\":\"\",\"repetir\":\"0\",\"zz_locucao_repeticao\":\"\",\"zz_locucao_fechamento\":\"Extra - locucao Mais Barato Mesmo.mp3\",\"dataInicio\":\"13\\/02\\/2015\",\"horaInicio\":\"00\",\"minutoInicio\":\"00\",\"dataFim\":\"13\\/02\\/2015\",\"horaFim\":\"23\",\"minutoFim\":\"00\",\"repeticoesHora\":\"1\",\"dias\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\"]}\", \"{\"zz_locucao_abertura\":{\"nome\":\"ofertas fim de semana\",\"arquivo\":\"Extra - locucao ofertas fim de semana.mp3\",\"genero\":\"zz_locucao_abertura\",\"data_inicio\":\"2015-01-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":33.45},\"zz_locucao_produto-0\":{\"nome\":\"bicicleta caloi 21 velocidades\",\"arquivo\":\"Extra - locucao bicicleta caloi 21 velocidades.mp3\",\"genero\":\"zz_locucao_produto_bazar\",\"data_inicio\":\"2015-01-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":5.89},\"zz_locucao_parcelamento-0\":{\"nome\":\"7 vezes de\",\"arquivo\":\"Extra - locucao 7 vezes de.mp3\",\"genero\":\"zz_locucao_parcelamento\",\"data_inicio\":\"2015-01-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":1.9},\"preco-real-0\":{\"nome\":\"5 Reais\",\"arquivo\":\"Extra - locucao 5 Reais.mp3\",\"genero\":\"zz_locucao_preco\",\"data_inicio\":\"2015-01-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":1.3},\"preco-centavos-0\":null,\"zz_locucao_fechamento\":{\"nome\":\"Mais Barato Mesmo\",\"arquivo\":\"Extra - locucao Mais Barato Mesmo.mp3\",\"genero\":\"zz_locucao_fechamento\",\"data_inicio\":\"2014-11-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":3.34}}\", 48, 1, 1, \"1,2,3,4,5,6,7\", \"2015-02-13\", \"2015-02-13\", 0, 82800, NOW(), NOW(), \'oferta\')','2015-02-13 16:36:53'),(5,1,'locucao','c',4,'INSERT INTO locucao(titulo, post, sequencia, tempo, repeticoes_hora, ativo, dia_semana, data_inicio, data_fim, hora_inicio, hora_fim, data_criacao, data_edicao, tipo) VALUES(\"asda\", \"{\"id\":\"0\",\"titulo\":\"asda\",\"zz_locucao_avisos\":\"Extra - locucao aviso pao quentinho.mp3\",\"dataInicio\":\"13\\/02\\/2015\",\"horaInicio\":\"00\",\"minutoInicio\":\"00\",\"dataFim\":\"13\\/02\\/2015\",\"horaFim\":\"23\",\"minutoFim\":\"00\",\"repeticoesHora\":\"1\",\"dias\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\"]}\", \"{\"zz_locucao_avisos\":{\"nome\":\"aviso pao quentinho\",\"arquivo\":\"Extra - locucao aviso pao quentinho.mp3\",\"genero\":\"zz_locucao_avisos\",\"data_inicio\":\"2015-01-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":28.26}}\", 31, 1, 1, \"1,2,3,4,5,6,7\", \"2015-02-13\", \"2015-02-13\", 0, 82800, NOW(), NOW(), \'aviso\')','2015-02-13 16:37:12'),(6,1,'locucao','u',4,'UPDATE locucao SET titulo=\"asda\", post=\"{\"id\":\"4\",\"titulo\":\"asda\",\"zz_locucao_avisos\":\"Extra - locucao aviso pao quentinho.mp3\",\"dataInicio\":\"13\\/02\\/2015\",\"horaInicio\":\"00\",\"minutoInicio\":\"00\",\"dataFim\":\"13\\/02\\/2015\",\"horaFim\":\"23\",\"minutoFim\":\"00\",\"repeticoesHora\":\"6\",\"dias\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\"]}\", sequencia=\"{\"zz_locucao_avisos\":{\"nome\":\"aviso pao quentinho\",\"arquivo\":\"Extra - locucao aviso pao quentinho.mp3\",\"genero\":\"zz_locucao_avisos\",\"data_inicio\":\"2015-01-01\",\"data_fim\":\"2038-01-01\",\"hora_inicio\":\"0\",\"hora_fim\":\"86399\",\"dia_semana\":\"1,2,3,4,5,6,7\",\"tempo\":28.26}}\", tempo=31, repeticoes_hora=6, ativo=1, dia_semana=\"1,2,3,4,5,6,7\", data_inicio=\"2015-02-13\", hora_inicio=0, hora_fim=82800, data_fim=\"2015-02-13\", data_edicao=NOW() WHERE id=4','2015-02-13 16:46:30'),(7,1,'locucao','d',4,'UPDATE locucao SET deletado=1 WHERE id=4','2015-02-13 17:53:00'),(8,1,'locucao','d',3,'UPDATE locucao SET deletado=1 WHERE id=3','2015-02-13 17:53:02');
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programacao`
--

DROP TABLE IF EXISTS `programacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programacao` (
  `locucao_id` int(11) DEFAULT NULL,
  `arquivo` varchar(100) DEFAULT NULL,
  `inicio` int(6) NOT NULL,
  `tempo` int(11) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programacao`
--

LOCK TABLES `programacao` WRITE;
/*!40000 ALTER TABLE `programacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `programacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `senha` varchar(40) NOT NULL,
  `tipo` int(1) NOT NULL DEFAULT '1' COMMENT '0=admin, 1=user',
  `deletado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'admin','admin','1fdb5b5ee342bbfeed7b043e1a9f0622abb9e0a4',0,0),(2,'teste1','teste','1fdb5b5ee342bbfeed7b043e1a9f0622abb9e0a4',1,1),(3,'Cintia Nakada','cintia','7110eda4d09e062aa5e4a390b0a572ac0d2c0220',1,0);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-02-23 17:03:44
