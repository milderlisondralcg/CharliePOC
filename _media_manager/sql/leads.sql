/*    ==Scripting Parameters==

    Source Database Engine Edition : Microsoft Azure SQL Database Edition
    Source Database Engine Type : Microsoft Azure SQL Database

    Target Database Engine Edition : Microsoft Azure SQL Database Edition
    Target Database Engine Type : Microsoft Azure SQL Database
*/

/****** Object:  Table [dbo].[Leads]    Script Date: 5/15/2018 11:32:55 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Leads](
	[Id] [int] IDENTITY(1,1) NOT NULL,
	[FirstName] [varchar](max) NOT NULL,
	[LastName] [varchar](max) NOT NULL,
	[Organization] [varchar](max) NULL,
	[Country] [varchar](50) NOT NULL,
	[Phone] [varchar](max) NULL,
	[Email] [varchar](100) NOT NULL,
	[IpAddress] [varchar](100) NULL,
	[CreatedDatetime] [datetime] NULL,
	[Debug] [char](10) NULL,
	[DebugEmail] [varchar](max) NULL,
	[Oid] [varchar](max) NULL,
	[LeadSource] [varchar](max) NULL,
	[RetUrl] [text] NULL,
	[Salutation] [varchar](50) NULL,
	[State] [varbinary](50) NULL,
	[MemberStatus] [varbinary](50) NULL,
	[Fax] [varbinary](50) NULL,
	[Mobile] [varbinary](50) NULL,
	[Address1] [varchar](50) NULL,
	[Address2] [varchar](50) NULL,
	[City] [varchar](max) NULL,
	[Zipcode] [varchar](50) NULL,
	[CampaignId] [varchar](50) NULL,
	[UID] [int] NULL
)
GO

