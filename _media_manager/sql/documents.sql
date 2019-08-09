/*    ==Scripting Parameters==

    Source Database Engine Edition : Microsoft Azure SQL Database Edition
    Source Database Engine Type : Microsoft Azure SQL Database

    Target Database Engine Edition : Microsoft Azure SQL Database Edition
    Target Database Engine Type : Microsoft Azure SQL Database
*/

/****** Object:  Table [dbo].[Documents]    Script Date: 5/15/2018 11:20:13 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Documents](
	[Id] [int] IDENTITY(1,1) NOT NULL,
	[Title] [varchar](max) NULL,
	[Description] [text] NULL,
	[Category] [varchar](50) NULL,
	[Tags] [varchar](50) NULL,
	[SalesForceId] [varchar](50) NULL,
	[AlternateTrackingId] [varchar](50) NULL,
	[UserId] [int] NULL,
	[FileName] [varchar](max) NULL,
	[EndpointURL] [varchar](max) NULL,
	[CreatedDateTime] [datetime] NULL,
	[ModifiedDateTime] [datetime] NULL,
	[LeadRequired] [char](10) NULL,
	[EmailNotify] [varchar](max) NULL,
	[CreatedBy] [int] NULL,
	[AppCode] [varchar](max) NULL,
	[ProductGroup] [varchar](max) NULL,
 CONSTRAINT [PK_Documents] PRIMARY KEY CLUSTERED 
(
	[Id] ASC
)WITH (STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF)
)
GO

